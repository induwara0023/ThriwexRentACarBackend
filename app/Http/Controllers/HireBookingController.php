<?php

namespace App\Http\Controllers;

use App\Models\HireBooking;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HireBookingController extends Controller
{
    public function index()
    {
        return response()->json(HireBooking::with(['vehicle', 'driver', 'customer'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name_manual' => 'nullable|string',
            'distance_km' => 'required|numeric',
            'rate_per_km' => 'required|numeric',
            'total_price' => 'required|numeric',
            'booking_date' => 'required|date',
            'destination' => 'nullable|string',
        ]);

        $hire = HireBooking::create($validated);

        // Update vehicle and driver status
        Vehicle::find($request->vehicle_id)->update(['status' => 'rented']); // Or 'on_hire' if we add that status
        Driver::find($request->driver_id)->update(['status' => 'on_hire']);

        return response()->json($hire, 201);
    }

    public function complete(HireBooking $hire)
    {
        $hire->update(['status' => 'completed']);

        // Free up vehicle and driver
        $hire->vehicle->update(['status' => 'available']);
        $hire->driver->update(['status' => 'available']);

        return response()->json(['message' => 'Hire completed successfully']);
    }

    public function destroy(HireBooking $hire)
    {
        // Reset status if ongoing
        if ($hire->status === 'ongoing') {
            $hire->vehicle->update(['status' => 'available']);
            $hire->driver->update(['status' => 'available']);
        }
        $hire->delete();
        return response()->json(['message' => 'Hire deleted successfully']);
    }
}
