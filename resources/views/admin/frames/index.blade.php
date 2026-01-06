@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">

        <!-- Admin Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-[Poppins] font-bold text-white">Frame Manager</h2>
                <p class="text-gray-400">Manage overlays for the photobooth</p>
            </div>
            <a href="{{ route('admin.logout') }}"
                class="px-5 py-2.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-300 border border-red-500/20 transition-all text-sm font-medium">
                Sign Out
            </a>
        </div>

        @if (session('success'))
            <div id="alert-box"
                class="mb-8 p-4 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center animate-fade-in-up">
                <svg class="h-5 w-5 text-green-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-green-300">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Upload Section (Left Column) -->
            <div class="lg:col-span-1">
                <div class="glass-card p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Upload New Frame
                    </h3>

                    <form action="{{ route('admin.frames.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Frame Name</label>
                            <input type="text" name="name"
                                class="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                required placeholder="e.g. Summer Vibes">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Frame Image (PNG)</label>
                            <div class="relative group">
                                <input type="file" name="image"
                                    class="w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 transition-all cursor-pointer border border-white/10 rounded-lg"
                                    accept="image/png,image/jpeg" required>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Recommended size: 720x240px (Transparent PNG)</p>
                        </div>

                        <button
                            class="w-full py-3 rounded-lg font-bold text-white bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 shadow-lg transition-all mt-4">
                            Upload Frame
                        </button>
                    </form>
                </div>
            </div>

            <!-- List Section (Right Column) -->
            <div class="lg:col-span-2">
                <h3 class="text-xl font-bold text-white mb-6">Active Frames ({{ $frames->count() }})</h3>

                @if ($frames->count() == 0)
                    <div class="text-center py-12 rounded-2xl border border-dashed border-white/10 bg-white/5">
                        <p class="text-gray-400">No frames uploaded yet.</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach ($frames as $frame)
                        <div
                            class="group relative bg-gray-900 rounded-xl overflow-hidden border border-white/10 shadow-lg hover:border-cyan-500/50 transition-all duration-300">
                            <!-- Image Preview -->
                            <div class="aspect-[3/1] bg-gray-800 relative">
                                <!-- Checkerboard pattern for transparency -->
                                <div class="absolute inset-0 opacity-20"
                                    style="background-image: radial-gradient(#444 1px, transparent 1px); background-size: 10px 10px;">
                                </div>
                                <img src="{{ asset('storage/' . $frame->image_path) }}"
                                    class="relative z-10 w-full h-full object-contain p-2" alt="Frame">
                            </div>

                            <!-- Actions -->
                            <div class="p-4 flex items-center justify-between bg-white/5">
                                <div>
                                    <h4 class="font-bold text-white text-sm">{{ $frame->name }}</h4>
                                    <span class="text-xs text-green-400 flex items-center mt-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 mr-1.5"></span> Active
                                    </span>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.frames.edit', $frame->id) }}"
                                        class="p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-cyan-500 hover:text-white transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.frames.destroy', $frame->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this frame?');">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="p-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-red-500 hover:text-white transition-colors"
                                            title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            setTimeout(() => {
                const alert = document.getElementById('alert-box');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 3000);
        </script>
    @endpush
@endsection