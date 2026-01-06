@extends('layouts.app')

@section('content')
  <div class="flex flex-col items-center justify-center min-h-[70vh] text-center">

    <!-- Hero Badge -->
    <div
      class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 border border-white/20 backdrop-blur-md mb-8 animate-bounce">
      <span class="w-2 h-2 rounded-full bg-green-400 mr-2 animate-pulse"></span>
      <span class="text-sm font-medium text-white tracking-wide">Camera Ready</span>
    </div>

    <!-- Main Title -->
    <h1 class="text-6xl md:text-8xl font-[Poppins] font-extrabold tracking-tight mb-6 drop-shadow-2xl">
      <span class="text-white">Capture the</span> <br>
      <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 via-purple-400 to-cyan-400">Moment.</span>
    </h1>

    <!-- Subtitle -->
    <p class="text-lg md:text-2xl text-gray-200 max-w-2xl font-light leading-relaxed mb-10">
      The most fun you'll have with your camera today. Snap, frame, and share your best poses instantly.
    </p>

    <!-- CTAs -->
    <div class="flex flex-col md:flex-row items-center gap-6 w-full md:w-auto">
      <a href="{{ url('/capture') }}"
        class="group relative px-8 py-4 bg-white text-gray-900 rounded-full font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 w-full md:w-auto">
        <span class="relative z-10 flex items-center justify-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-500 group-hover:rotate-12 transition-transform"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Start Photo
        </span>
      </a>

      <a href="{{ url('/photos') }}"
        class="px-8 py-4 rounded-full font-bold text-lg text-white border-2 border-white/20 hover:bg-white/10 hover:border-white/40 transition-all w-full md:w-auto">
        View Gallery
      </a>
    </div>

  </div>
@endsection