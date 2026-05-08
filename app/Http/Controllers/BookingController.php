<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingMedia;
use App\Models\Vehicle;
use App\Http\Resources\BookingResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        return BookingResource::collection(Booking::with(['vehicle', 'customer', 'media'])->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'customer_id' => 'required|exists:customers,id',
            'pickup_datetime' => 'required|date',
            'return_datetime' => 'nullable|date',
            'pickup_km' => 'required|integer',
            'advance_payment' => 'required|numeric',
            'security_item_description' => 'nullable|string',
            'selfie' => 'nullable|image',
            'nic_front' => 'nullable|image',
            'nic_back' => 'nullable|image',
            'agreement' => 'nullable|image',
            'security_item' => 'nullable|image',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            // 1. Create Booking
            $booking = Booking::create($validated);

            // 2. Handle Media Uploads
            $mediaTypes = ['selfie', 'nic_front', 'nic_back', 'agreement', 'security_item'];
            foreach ($mediaTypes as $type) {
                if ($request->hasFile($type)) {
                    $path = $request->file($type)->store('bookings/' . $booking->id, 'public');
                    BookingMedia::create([
                        'booking_id' => $booking->id,
                        'type' => $type,
                        'file_path' => $path,
                    ]);
                }
            }

            // 3. Update Vehicle Status
            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            $vehicle->update(['status' => 'rented']);

            return new BookingResource($booking->load(['vehicle', 'customer', 'media']));
        });
    }

    public function complete(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'return_km' => 'required|integer|gte:' . $booking->pickup_km,
            'return_datetime' => 'required|date',
        ]);

        return DB::transaction(function () use ($booking, $validated) {
            $vehicle = $booking->vehicle;
            
            // Calculation Logic
            $pickupDate = Carbon::parse($booking->pickup_datetime);
            $returnDate = Carbon::parse($validated['return_datetime']);
            $days = ceil($pickupDate->diffInHours($returnDate) / 24);
            $days = max(1, $days);

            $dayCharge = $days * ($vehicle->daily_rate ?? 0);
            
            $totalKm = $validated['return_km'] - $booking->pickup_km;
            $allowedKm = $days * ($vehicle->km_limit_per_day ?? 0);
            $excessKm = max(0, $totalKm - $allowedKm);
            $excessCharge = $excessKm * ($vehicle->extra_km_rate ?? 0);
            
            $totalPrice = $dayCharge + $excessCharge;
            $balanceDue = $totalPrice - ($booking->advance_payment ?? 0);

            // 1. Update Booking
            $booking->update([
                'return_km' => $validated['return_km'],
                'return_datetime' => Carbon::parse($validated['return_datetime']),
                'total_price' => $totalPrice,
                'status' => 'completed',
            ]);

            // 2. Update Vehicle Status and Mileage
            $vehicle->update([
                'status' => 'available',
                'current_km' => $validated['return_km']
            ]);

            return response()->json([
                'message' => 'Booking completed successfully',
                'summary' => [
                    'days' => $days,
                    'total_km' => $totalKm,
                    'excess_km' => $excessKm,
                    'day_charge' => $dayCharge,
                    'excess_charge' => $excessCharge,
                    'total_price' => $totalPrice,
                    'advance_payment' => $booking->advance_payment,
                    'balance_due' => $balanceDue
                ]
            ]);
        });
    }
}
