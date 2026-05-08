<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\AuthController;

// Auth Routes
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
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
