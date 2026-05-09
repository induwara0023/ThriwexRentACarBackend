<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\AuthController;

// Auth Routes
Route::post('login', [AuthController::class, 'login']);

Route::get('create-admin-user', function() {
    // Create first admin
    $admin1 = \App\Models\User::where('email', 'admin@thriwex.com')->first();
    if (!$admin1) {
        \App\Models\User::create([
            'name' => 'System Admin',
            'email' => 'admin@thriwex.com',
            'password' => 'admin123'
        ]);
    } else {
        $admin1->update(['password' => 'admin123']);
    }

    // Create second test admin
    $admin2 = \App\Models\User::where('email', 'test@thriwex.com')->first();
    if (!$admin2) {
        \App\Models\User::create([
            'name' => 'Test Admin',
            'email' => 'test@thriwex.com',
            'password' => 'test1234'
        ]);
    } else {
        $admin2->update(['password' => 'test1234']);
    }

    return "Users Prepared (No double-hash)! 1: admin@thriwex.com (admin123), 2: test@thriwex.com (test1234)";
});

Route::group([], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'me']);

    // Customer Routes
    Route::get('customers', [CustomerController::class, 'index']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::get('customers/search/{nic}', [CustomerController::class, 'search']);

    // Booking Routes
    Route::post('bookings/store', [BookingController::class, 'store']);
    Route::post('bookings/{booking}/complete', [BookingController::class, 'complete']);
    Route::get('bookings', [BookingController::class, 'index']); 

    // Fleet Routes
    Route::get('fleet/alerts', [FleetController::class, 'alerts']);
    Route::get('vehicles', [FleetController::class, 'index']);
    Route::post('vehicles', [FleetController::class, 'store']);
    Route::put('vehicles/{vehicle}', [FleetController::class, 'update']);
    Route::get('vehicles/{vehicle}/history', [FleetController::class, 'history']);
    Route::get('vehicles/available', [FleetController::class, 'available']);

    // Dashboard Summary
    Route::get('dashboard/summary', [DashboardController::class, 'index']);
});
