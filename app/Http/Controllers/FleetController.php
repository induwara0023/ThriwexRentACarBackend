<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Booking;
use App\Http\Resources\VehicleResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    public function alerts()
    {
        $today = Carbon::today();
        $thirtyDaysFromNow = Carbon::today()->addDays(30);

        $alerts = Vehicle::where(function ($query) {
            $query->whereRaw('next_service_km - current_km <= 500');
        })
        ->orWhere(function ($query) use ($thirtyDaysFromNow) {
            $query->whereDate('license_expiry', '<=', $thirtyDaysFromNow);
        })
        ->orWhere(function ($query) use ($thirtyDaysFromNow) {
            $query->whereDate('insurance_expiry', '<=', $thirtyDaysFromNow);
        })
        ->get();

        return VehicleResource::collection($alerts);
    }

    public function index()
    {
        return VehicleResource::collection(Vehicle::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_no' => 'required|unique:vehicles',
            'model' => 'required|string',
            'type' => 'required|in:Car,Van,SUV',
            'transmission' => 'required|in:Auto,Manual',
            'current_km' => 'required|integer',
            'next_service_km' => 'required|integer',
            'insurance_expiry' => 'required|date',
            'license_expiry' => 'required|date',
            'daily_rate' => 'required|numeric',
            'km_limit_per_day' => 'required|integer',
            'extra_km_rate' => 'required|numeric',
            'status' => 'required|in:available,rented,maintenance',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle = Vehicle::create($validated);
        return new VehicleResource($vehicle);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_no' => 'required|unique:vehicles,plate_no,' . $vehicle->id,
            'model' => 'required|string',
            'type' => 'required|in:Car,Van,SUV',
            'transmission' => 'required|in:Auto,Manual',
            'current_km' => 'required|integer',
            'next_service_km' => 'required|integer',
            'insurance_expiry' => 'required|date',
            'license_expiry' => 'required|date',
            'daily_rate' => 'required|numeric',
            'km_limit_per_day' => 'required|integer',
            'extra_km_rate' => 'required|numeric',
            'status' => 'required|in:available,rented,maintenance',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update($validated);
        return new VehicleResource($vehicle);
    }

    public function history(Vehicle $vehicle)
    {
        $history = $vehicle->bookings()->with('customer')->latest()->take(10)->get();
        return response()->json(['data' => $history]);
    }

    public function available(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $startTime = $request->start_time;
        $endTime = $request->end_time;

        // Logic: requested_start < existing_end AND requested_end > existing_start
        $unavailableVehicleIds = Booking::where('status', 'ongoing')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('pickup_datetime', '<', $endTime)
                      ->where('return_datetime', '>', $startTime);
            })
            ->pluck('vehicle_id');

        $availableVehicles = Vehicle::whereNotIn('id', $unavailableVehicleIds)
            ->where('status', '!=', 'maintenance')
            ->get();

        return VehicleResource::collection($availableVehicles);
    }
}
