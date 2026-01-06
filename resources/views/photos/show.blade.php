@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb / Back -->
        <div class="mb-6">
            <a href="{{ url('/photos') }}"
                class="inline-flex items-center text-gray-300 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back to Gallery
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Photo Container -->
            <div class="relative bg-black rounded-2xl shadow-2xl overflow-hidden border border-white/10 group">
                <img src="{{ asset('storage/' . $photo->filename) }}" class="w-full h-auto block" alt="Captured Photo">

                <!-- Download Button Overlay -->
                <a href="{{ asset('storage/' . $photo->filename) }}" download
                    class="absolute top-4 right-4 p-2 bg-black/50 hover:bg-black/70 rounded-full text-white backdrop-blur transition-all opacity-0 group-hover:opacity-100 ring-1 ring-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
            </div>

            <!-- Details Panel -->
            <div class="flex flex-col">
                <div class="glass-card p-6 flex-grow">
                    <h3 class="text-xl font-bold text-white mb-2">Photo Details</h3>

                    <div class="space-y-4 text-gray-300 text-sm mb-8">
                        <div class="flex justify-between border-b border-white/10 py-2">
                            <span>Date Captured</span>
                            <span class="text-white">{{ $photo->created_at->format('M d, Y • h:i A') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-white/10 py-2">
                            <span>Frame Used</span>
                            <span class="text-white">{{ $photo->frame ? $photo->frame->name : 'None' }}</span>
                        </div>
                    </div>

                    @if($photo->caption)
                        <div class="bg-white/5 p-4 rounded-xl border border-white/5 mb-6">
                            <p class="text-white italic">"{{ $photo->caption }}"</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <a href="{{ url('/photos/' . $photo->id . '/edit') }}"
                            class="flex items-center justify-center px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl text-white font-semibold transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit
                        </a>

                        <form action="{{ url('/photos/' . $photo->id) }}" method="POST"
                            onsubmit="return confirm('Delete this photo?');">
                            @csrf
                            @method('DELETE')
                            <button
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-500/20 hover:bg-red-500/30 text-red-300 rounded-xl font-semibold transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection