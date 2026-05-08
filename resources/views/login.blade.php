@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-900 flex items-center justify-center p-4 relative overflow-hidden">
  <!-- Background Effects -->
  <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary-600/20 rounded-full blur-3xl"></div>
  <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl"></div>

  <div class="w-full max-w-md bg-slate-800/50 backdrop-blur-xl rounded-3xl p-8 border border-slate-700 shadow-2xl relative z-10">

    <div class="text-center mb-10">
      <div class="w-16 h-16 bg-primary-500 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20 mx-auto mb-6">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-white tracking-tight">Thriwex<span class="text-primary-400">Rent</span></h1>
      <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold mt-2">Customer Portal</p>
    </div>

    <form method="POST" action="{{ route('customer.login') }}" class="space-y-6">
      @csrf
      
      @if ($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-400 text-sm font-medium text-center">
          {{ $errors->first() }}
        </div>
      @endif

      @if (session('error'))
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-400 text-sm font-medium text-center">
          {{ session('error') }}
        </div>
      @endif

      <div class="space-y-2">
        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required
          class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3.5 text-sm text-white font-medium focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-600 outline-none @error('email') border-rose-500 @enderror"
          placeholder="customer@example.com">
        @error('email')
          <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="space-y-2">
        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Password</label>
        <input type="password" name="password" required
          class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3.5 text-sm text-white font-medium focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-600 outline-none @error('password') border-rose-500 @enderror"
          placeholder="••••••••">
        @error('password')
          <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit"
        class="w-full bg-primary-600 text-white py-4 rounded-xl font-bold uppercase tracking-widest text-[11px] hover:bg-primary-500 transition-all shadow-xl shadow-primary-600/20">
        Sign In
      </button>
    </form>

    <div class="mt-8 text-center border-t border-slate-700/50 pt-6">
      <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Secure & Private</p>
    </div>
  </div>
</div>
@endsection