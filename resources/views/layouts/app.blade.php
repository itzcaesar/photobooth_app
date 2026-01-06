<!doctype html>
<html lang="en" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poselab - Photobooth</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;600;700;800&display=swap"
    rel="stylesheet">

  <!-- Scripts & Styles -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* Animated Background */
    body {
      background: linear-gradient(-45deg, #1E1B4B, #4c1d95, #831843, #1e3a8a);
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
      min-height: 100vh;
      color: white;
      display: flex;
      flex-direction: column;
    }

    @keyframes gradientBG {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }
  </style>
</head>

<body class="font-sans antialiased selection:bg-pink-500 selection:text-white">

  <!-- Navbar -->
  <nav class="fixed top-0 w-full z-50 transition-all duration-300 glass border-b-0 border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <a href="/" class="text-3xl font-bold font-[Poppins] tracking-tight">
            <span class="text-white">Pose</span><span class="text-pink-400">lab</span><span
              class="text-cyan-400">.</span>
          </a>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-8 items-center">
          <a href="{{ url('/capture') }}"
            class="text-gray-300 hover:text-white hover:bg-white/10 px-4 py-2 rounded-full transition-all font-medium text-sm tracking-wide">
            Capture
          </a>
          <a href="{{ url('/photos') }}"
            class="text-gray-300 hover:text-white hover:bg-white/10 px-4 py-2 rounded-full transition-all font-medium text-sm tracking-wide">
            Gallery
          </a>

          @if(session('user_id'))
            <div class="relative group">
              <button
                class="flex items-center space-x-2 text-white bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full transition-all border border-white/10">
                <span class="text-sm font-semibold">Hi, {{ session('user_name') }}</span>
              </button>
              <!-- Dropdown -->
              <div
                class="absolute right-0 mt-2 w-48 bg-gray-900 rounded-xl shadow-2xl py-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all transform origin-top-right border border-gray-700">
                <a href="{{ url('/logout') }}"
                  class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white">Logout</a>
              </div>
            </div>
          @else
            <div class="flex items-center space-x-4">
              <a href="{{ url('/login') }}" class="text-white font-medium hover:text-pink-300 transition-colors">Login</a>
              <a href="{{ url('/register') }}"
                class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-400 hover:to-purple-500 text-white px-5 py-2.5 rounded-full font-bold shadow-lg hover:shadow-pink-500/25 transition-all transform hover:-translate-y-0.5">
                Sign Up
              </a>
            </div>
          @endif
        </div>

        <!-- Mobile menu button -->
        <div class="flex items-center md:hidden">
          <button type="button" class="text-gray-300 hover:text-white focus:outline-none"
            onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-gray-900/95 backdrop-blur-xl border-t border-gray-800">
      <div class="px-4 pt-2 pb-6 space-y-2">
        <a href="{{ url('/capture') }}"
          class="block text-gray-300 hover:text-white hover:bg-white/5 px-3 py-3 rounded-lg text-lg font-medium">Capture</a>
        <a href="{{ url('/photos') }}"
          class="block text-gray-300 hover:text-white hover:bg-white/5 px-3 py-3 rounded-lg text-lg font-medium">Gallery</a>
        @if(session('user_id'))
          <div class="border-t border-gray-700 my-2 pt-2">
            <span class="block text-gray-400 text-sm px-3 mb-2">Signed in as {{ session('user_name') }}</span>
            <a href="{{ url('/logout') }}"
              class="block text-red-400 hover:text-red-300 px-3 py-2 rounded-lg text-base font-medium">Logout</a>
          </div>
        @else
          <div class="grid grid-cols-2 gap-4 mt-4">
            <a href="{{ url('/login') }}"
              class="text-center py-3 rounded-xl bg-gray-800 text-white font-semibold">Login</a>
            <a href="{{ url('/register') }}" class="text-center py-3 rounded-xl bg-pink-600 text-white font-semibold">Sign
              Up</a>
          </div>
        @endif
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="flex-grow pt-24 pb-12 px-4 sm:px-6 lg:px-8 relative z-0">
    <div class="max-w-7xl mx-auto h-full w-full">
      @yield('content')
    </div>
  </main>

  <!-- Simple Footer -->
  <footer class="w-full py-6 text-center text-white/40 text-sm glass border-t border-white/5 mt-auto">
    <p>&copy; {{ date('Y') }} Poselab. Captured with Style.</p>
  </footer>

  @stack('scripts')
</body>

</html>