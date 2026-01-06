@extends('layouts.app')

@section('content')
  <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="w-full max-w-md">

      <!-- Glass Card -->
      <div class="glass-card p-8 md:p-10 relative overflow-hidden">
        <!-- Decorative Glow -->
        <div
          class="absolute -top-20 -right-20 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-30 pointer-events-none">
        </div>
        <div
          class="absolute -bottom-20 -left-20 w-40 h-40 bg-pink-500 rounded-full blur-3xl opacity-30 pointer-events-none">
        </div>

        <h3 class="text-3xl font-[Poppins] font-bold text-white mb-2 text-center">Welcome Back!</h3>
        <p class="text-gray-300 text-center mb-8 text-sm">Login to access your photos.</p>

        <form method="POST" action="{{ url('/login') }}" class="space-y-6">
          @csrf

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-300 ml-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}"
              class="w-full px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
              placeholder="you@example.com">
            @error('email') <div class="text-red-400 text-xs ml-1">{{ $message }}</div> @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-300 ml-1">Password</label>
            <input type="password" name="password"
              class="w-full px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
              placeholder="••••••••">
          </div>

          <button
            class="w-full py-3.5 rounded-xl font-bold text-white shadow-lg bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-400 hover:to-purple-500 transform hover:-translate-y-1 transition-all duration-200">
            Sign In
          </button>
        </form>

        <div class="mt-8 text-center">
          <p class="text-sm text-gray-400">
            Don't have an account?
            <a href="{{ url('/register') }}"
              class="text-pink-400 hover:text-pink-300 font-semibold hover:underline">Register here</a>
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection