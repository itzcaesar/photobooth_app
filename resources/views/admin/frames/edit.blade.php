@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="flex items-center mb-8">
            <a href="{{ route('admin.frames.index') }}"
                class="mr-4 p-2 rounded-lg hover:bg-white/10 text-gray-300 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-white">Edit Frame</h2>
        </div>

        <div class="glass-card p-8">
            <div class="flex flex-col items-center mb-8">
                <div
                    class="w-full h-32 bg-gray-900 rounded-xl flex items-center justify-center p-4 border border-white/10 mb-4 relative overflow-hidden">
                    <!-- Checkerboard -->
                    <div class="absolute inset-0 opacity-20"
                        style="background-image: radial-gradient(#444 1px, transparent 1px); background-size: 10px 10px;">
                    </div>
                    <img src="{{ asset('storage/' . $frame->image_path) }}"
                        class="relative z-10 max-h-full object-contain drop-shadow-lg" alt="{{ $frame->name }}">
                </div>
                <span class="text-sm text-gray-400">Current Image Preview</span>
            </div>

            <form action="{{ route('admin.frames.update', $frame->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Frame Name</label>
                    <input type="text"
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                        id="name" name="name" value="{{ old('name', $frame->name) }}" required>

                    @error('name')
                        <div class="text-red-400 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.frames.index') }}"
                        class="px-6 py-3 rounded-xl font-semibold text-gray-300 hover:text-white hover:bg-white/5 transition-colors">Cancel</a>

                    <button type="submit"
                        class="px-8 py-3 rounded-xl font-bold text-white bg-green-600 hover:bg-green-500 shadow-lg shadow-green-500/20 transition-all transform hover:-translate-y-0.5">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection