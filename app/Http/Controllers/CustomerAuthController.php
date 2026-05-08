<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Try to authenticate the customer
        if (Auth::guard('customer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/customer-dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully!');
    }

    public function dashboard(): View|RedirectResponse
    {
        // Double-check authentication
        if (!Auth::guard('customer')->check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $customer = Auth::guard('customer')->user();
        $bookings = $customer->bookings()->with('vehicle')->latest()->get();
        
        return view('customer-dashboard', [
            'customer' => $customer,
            'bookings' => $bookings,
        ]);
    }
}
