<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerAuthController;

Route::get('/', function () {
    return view('welcome');
});


// Customer Authentication Routes
Route::middleware('guest:customer')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login');
});

Route::middleware('auth:customer')->group(function () {
    Route::get('/customer-dashboard', [CustomerAuthController::class, 'dashboard'])->name('customer.dashboard');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
});
