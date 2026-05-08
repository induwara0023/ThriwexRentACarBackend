<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Http\Resources\BookingResource;
use App\Http\Resources\VehicleResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thirtyDaysFromNow = Carbon::today()->addDays(30);

        // 1. Top Summary Stats
        $stats = [
            'total_fleet' => Vehicle::count(),
            'available_now' => Vehicle::where('status', 'available')->count(),
            'ongoing_hires' => Vehicle::where('status', 'rented')->count(),
        ];

        // 2. Today's Returns (Bookings due today)
        $todayReturns = Booking::with(['vehicle', 'customer'])
            ->where('status', 'ongoing')
            ->whereDate('return_datetime', $today)
            ->get();

        // 3. Smart Reminders: Service Alerts
        $serviceAlerts = Vehicle::whereRaw('next_service_km - current_km <= 500')->get();

        // 4. Smart Reminders: Document Alerts
        $docAlerts = Vehicle::where(function ($query) use ($thirtyDaysFromNow) {
            $query->whereDate('license_expiry', '<=', $thirtyDaysFromNow)
                  ->orWhereDate('insurance_expiry', '<=', $thirtyDaysFromNow);
        })->get();

        $lateReturns = Booking::with(['vehicle', 'customer'])
            ->where('status', 'ongoing')
            ->whereDate('return_datetime', '<', $today)
            ->get();

        // 6. All Ongoing Rentals
        $ongoingRentals = Booking::with(['vehicle', 'customer'])
            ->where('status', 'ongoing')
            ->latest()
            ->get();

        return response()->json([
            'stats' => $stats,
            'today_returns' => BookingResource::collection($todayReturns),
            'service_alerts' => VehicleResource::collection($serviceAlerts),
            'document_alerts' => VehicleResource::collection($docAlerts),
            'late_returns' => BookingResource::collection($lateReturns),
            'ongoing_rentals' => BookingResource::collection($ongoingRentals),
        ]);
    }
}
