@extends('layouts.app')

@section('content')
  <div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-bold font-[Poppins] text-white">My Gallery</h2>
    <a href="{{ url('/capture') }}"
      class="hidden md:inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-medium transition-all">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      New Photo
    </a>
  </div>

  @if($photos->count() == 0)
    <div class="text-center py-20 rounded-3xl border-2 border-dashed border-white/10 bg-white/5">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/5 mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
      <h3 class="text-xl font-medium text-white mb-2">No photos yet</h3>
      <p class="text-gray-400 mb-6">Looks like you haven't captured any memories yet.</p>
      <a href="{{ url('/capture') }}"
        class="inline-flex items-center px-6 py-3 bg-pink-600 hover:bg-pink-500 text-white rounded-full font-bold shadow-lg transition-transform hover:scale-105">
        Start Capturing
      </a>
    </div>
  @else
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @foreach($photos as $photo)
        <div
          class="group relative aspect-[3/4] rounded-2xl overflow-hidden bg-gray-900 shadow-xl border border-white/10 transition-transform duration-300 hover:-translate-y-2">

          <!-- Image -->
          <img src="{{ asset('storage/' . $photo->thumb_path) }}"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="Photo">

          <!-- Hover Overlay -->
          <div
            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
            <p class="text-white font-medium truncate">{{ $photo->caption ?? 'Untitled' }}</p>
            <p class="text-xs text-gray-400 mb-3">{{ $photo->created_at->diffForHumans() }}</p>

            <a href="{{ url('/photos/' . $photo->id) }}"
              class="block w-full py-2 bg-white text-gray-900 text-center rounded-lg font-bold text-sm hover:bg-gray-200 transition-colors">
              View
            </a>
          </div>

          <!-- Frame Badge -->
          @if($photo->frame)
            <div
              class="absolute top-2 right-2 px-2 py-1 bg-black/50 backdrop-blur text-xs rounded text-white border border-white/10">
              {{ $photo->frame->name }}
            </div>
          @endif
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
      {{ $photos->links() }}
    </div>
  @endif
@endsection