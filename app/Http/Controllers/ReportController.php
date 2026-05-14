<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function income(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $vehicleId = $request->input('vehicle_id');

        $query = Booking::where('status', 'completed');

        if ($vehicleId) {
            $query->where('vehicle_id', $vehicleId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('return_datetime', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $totalIncome = $query->sum('total_price');

        // Vehicle Income
        $vehicleIncomeQuery = Vehicle::withSum(['bookings as total_earned' => function($q) use ($startDate, $endDate) {
            $q->where('status', 'completed');
            if ($startDate && $endDate) {
                $q->whereBetween('return_datetime', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            }
        }], 'total_price');

        if ($vehicleId) {
            $vehicleIncomeQuery->where('id', $vehicleId);
        }

        $vehicleIncome = $vehicleIncomeQuery->get()->map(function($v) {
            return [
                'id' => $v->id,
                'vehicle' => $v->model . ' (' . $v->plate_no . ')',
                'earned' => $v->total_earned ?? 0
            ];
        })->filter(function($v) {
            return $v['earned'] > 0;
        })->sortByDesc('earned')->values();

        // Monthly Income (for the chart or breakdown)
        $monthlyIncomeQuery = Booking::where('status', 'completed')
            ->select(
                DB::raw('DATE_FORMAT(return_datetime, "%Y-%m") as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc');

        if ($vehicleId) {
            $monthlyIncomeQuery->where('vehicle_id', $vehicleId);
        }

        if ($startDate && $endDate) {
            $monthlyIncomeQuery->whereBetween('return_datetime', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        } else {
            $monthlyIncomeQuery->where('return_datetime', '>=', Carbon::now()->subMonths(6));
        }

        $bookingsQuery = Booking::with(['customer', 'vehicle'])->where('status', 'completed');
        
        if ($vehicleId) {
            $bookingsQuery->where('vehicle_id', $vehicleId);
        }

        if ($startDate && $endDate) {
            $bookingsQuery->whereBetween('return_datetime', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $bookingLog = $bookingsQuery->orderBy('return_datetime', 'desc')->get()->map(function($b) {
            return [
                'id' => $b->id,
                'customer_name' => $b->customer ? $b->customer->name : 'N/A',
                'vehicle' => $b->vehicle ? $b->vehicle->model . ' (' . $b->vehicle->plate_no . ')' : 'N/A',
                'pickup_date' => Carbon::parse($b->pickup_datetime)->format('Y-m-d'),
                'return_date' => Carbon::parse($b->return_datetime)->format('Y-m-d'),
                'pickup_km' => $b->pickup_km,
                'return_km' => $b->return_km,
                'total_price' => $b->total_price
            ];
        });

        $monthlyIncome = $monthlyIncomeQuery->get();

        return response()->json([
            'total_income' => $totalIncome,
            'vehicle_income' => $vehicleIncome,
            'monthly_income' => $monthlyIncome,
            'booking_log' => $bookingLog
        ]);
    }

    public function usage(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $vehicleId = $request->input('vehicle_id');

        $query = Booking::with(['customer', 'vehicle'])->where('status', 'completed');
        
        if ($vehicleId) {
            $query->where('vehicle_id', $vehicleId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('return_datetime', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $bookingLog = $query->orderBy('return_datetime', 'desc')->get()->map(function($b) {
            return [
                'id' => $b->id,
                'customer_name' => $b->customer ? $b->customer->name : 'N/A',
                'vehicle' => $b->vehicle ? $b->vehicle->model . ' (' . $b->vehicle->plate_no . ')' : 'N/A',
                'pickup_date' => Carbon::parse($b->pickup_datetime)->format('Y-m-d'),
                'return_date' => Carbon::parse($b->return_datetime)->format('Y-m-d'),
                'pickup_km' => $b->pickup_km,
                'return_km' => $b->return_km,
                'total_price' => $b->total_price
            ];
        });

        return response()->json($bookingLog);
    }
}
