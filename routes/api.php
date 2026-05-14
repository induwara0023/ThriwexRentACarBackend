<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\HireBookingController;

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
    Route::put('customers/{customer}', [CustomerController::class, 'update']);
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy']);
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
    Route::delete('vehicles/{vehicle}', [FleetController::class, 'destroy']);
    Route::get('vehicles/{vehicle}/history', [FleetController::class, 'history']);
    Route::get('vehicles/available', [FleetController::class, 'available']);

    // Dashboard Summary
    Route::get('dashboard/summary', [DashboardController::class, 'index']);

    // Reports
    Route::get('reports/income', [ReportController::class, 'income']);
    Route::get('reports/usage', [ReportController::class, 'usage']);

    // Admin Management
    Route::get('admins', [AdminController::class, 'index']);
    Route::post('admins', [AdminController::class, 'store']);
    Route::put('admins/{admin}', [AdminController::class, 'update']);
    Route::delete('admins/{admin}', [AdminController::class, 'destroy']);

    // Driver Management
    Route::get('drivers', [DriverController::class, 'index']);
    Route::post('drivers', [DriverController::class, 'store']);
    Route::put('drivers/{driver}', [DriverController::class, 'update']);
    Route::delete('drivers/{driver}', [DriverController::class, 'destroy']);

    // Hire Bookings
    Route::get('hires', [HireBookingController::class, 'index']);
    Route::post('hires', [HireBookingController::class, 'store']);
    Route::post('hires/{hire}/complete', [HireBookingController::class, 'complete']);
    Route::delete('hires/{hire}', [HireBookingController::class, 'destroy']);
});

// Media Bridge (Fix for Windows Symlink issues)
Route::get('media/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*');
