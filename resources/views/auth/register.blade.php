@extends('layouts.app')

@section('content')
  <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="w-full max-w-md">

      <!-- Glass Card -->
      <div class="glass-card p-8 md:p-10 relative overflow-hidden">
        <h3 class="text-3xl font-[Poppins] font-bold text-white mb-2 text-center">Join the Fun</h3>
        <p class="text-gray-300 text-center mb-8 text-sm">Create an account to start saving memories.</p>

        <form method="POST" action="{{ url('/register') }}" class="space-y-5">
          @csrf

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-300 ml-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
              class="w-full px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
              placeholder="John Doe">
            @error('name') <div class="text-red-400 text-xs ml-1">{{ $message }}</div> @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-300 ml-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}"
              class="w-full px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
              placeholder="you@example.com">
            @error('email') <div class="text-red-400 text-xs ml-1">{{ $message }}</div> @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-300 ml-1">Password</label>
            <input type="password" name="password"
              class="w-full px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
              placeholder="••••••••">
            @error('password') <div class="text-red-400 text-xs ml-1">{{ $message }}</div> @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-300 ml-1">Confirm Password</label>
            <input type="password" name="password_confirmation"
              class="w-full px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
              placeholder="••••••••">
          </div>

          <button
            class="w-full py-3.5 rounded-xl font-bold text-white shadow-lg bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 transform hover:-translate-y-1 transition-all duration-200 mt-2">
            Create Account
          </button>
        </form>

        <div class="mt-8 text-center">
          <p class="text-sm text-gray-400">
            Already members?
            <a href="{{ url('/login') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold hover:underline">Login
              here</a>
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection