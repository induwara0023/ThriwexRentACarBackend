@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-900">
  <!-- Navigation -->
  <nav class="bg-slate-800/50 backdrop-blur-xl border-b border-slate-700 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-white">Thriwex<span class="text-primary-400">Rent</span></h1>
      </div>
      <form method="POST" action="{{ route('customer.logout') }}" class="inline">
        @csrf
        <button type="submit" class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-medium transition-all">
          Logout
        </button>
      </form>
    </div>
  </nav>

  <div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Welcome Section -->
    <div class="mb-12">
      <h2 class="text-4xl font-bold text-white mb-2">Welcome back, {{ Auth::guard('customer')->user()->name }}!</h2>
      <p class="text-slate-400">Manage your car rentals and bookings</p>
    </div>

    <!-- Customer Info Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
      <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl p-6 border border-slate-700">
        <p class="text-slate-400 text-sm uppercase tracking-wider mb-2">Email</p>
        <p class="text-white text-lg font-medium">{{ Auth::guard('customer')->user()->email }}</p>
      </div>
      <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl p-6 border border-slate-700">
        <p class="text-slate-400 text-sm uppercase tracking-wider mb-2">Phone</p>
        <p class="text-white text-lg font-medium">{{ Auth::guard('customer')->user()->phone }}</p>
      </div>
      <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl p-6 border border-slate-700">
        <p class="text-slate-400 text-sm uppercase tracking-wider mb-2">NIC Number</p>
        <p class="text-white text-lg font-medium">{{ Auth::guard('customer')->user()->nic_no }}</p>
      </div>
    </div>

    <!-- Bookings Section -->
    <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700 overflow-hidden">
      <div class="px-6 py-6 border-b border-slate-700">
        <h3 class="text-2xl font-bold text-white">Your Bookings</h3>
      </div>

      @if($bookings->count() > 0)
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-slate-300">
            <thead class="bg-slate-900/50 border-b border-slate-700">
              <tr>
                <th class="px-6 py-4 text-left font-semibold text-white">Vehicle</th>
                <th class="px-6 py-4 text-left font-semibold text-white">Start Date</th>
                <th class="px-6 py-4 text-left font-semibold text-white">End Date</th>
                <th class="px-6 py-4 text-left font-semibold text-white">Total Cost</th>
                <th class="px-6 py-4 text-left font-semibold text-white">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bookings as $booking)
                <tr class="border-b border-slate-700 hover:bg-slate-700/30 transition-colors">
                  <td class="px-6 py-4">
                    <p class="font-medium text-white">{{ $booking->vehicle->brand ?? 'N/A' }} {{ $booking->vehicle->model ?? '' }}</p>
                  </td>
                  <td class="px-6 py-4">{{ \Carbon\Carbon::parse($booking->pickup_datetime)->format('M d, Y') }}</td>
                  <td class="px-6 py-4">{{ \Carbon\Carbon::parse($booking->return_datetime)->format('M d, Y') }}</td>
                  <td class="px-6 py-4 font-medium text-primary-400">LKR {{ number_format($booking->total_price, 2) }}</td>
                  <td class="px-6 py-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                      @if($booking->status === 'completed') bg-emerald-900/30 text-emerald-400
                      @elseif($booking->status === 'active') bg-primary-900/30 text-primary-400
                      @else bg-slate-700 text-slate-300
                      @endif
                    ">
                      {{ ucfirst($booking->status) }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="px-6 py-12 text-center">
          <p class="text-slate-400 text-lg">No bookings yet. Start your first rental today!</p>
          <a href="/" class="inline-block mt-6 px-8 py-3 bg-primary-600 hover:bg-primary-500 text-white rounded-lg font-medium transition-all">
            Browse Vehicles
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection