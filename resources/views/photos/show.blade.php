@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        {{-- FOTO sekarang dibungkus HANYA untuk memudahkan batasan dimensi --}}
        <div style="max-width: 720px; margin: 0 auto;"> 
            {{-- PERBAIKAN KRITIS: Set max-height pada gambar untuk membatasi ukuran vertikal --}}
            <img 
                src="{{ asset('storage/' . $photo->filename) }}" 
                style="
                    width:100%; 
                    display:block;
                    /* Batasi tinggi maksimum gambar agar muat di layar */
                    max-height: 80vh; 
                    height: auto;
                    object-fit: contain;
                "
            >
        </div>
    </div>
    <div class="col-md-4">
        <h5>Details</h5>
        <p>Frame: {{ $photo->frame ? $photo->frame->name : 'None' }}</p>
        <p>Taken at: {{ $photo->created_at }}</p>

        <div class="d-flex flex-wrap gap-2 mb-2">
            <a href="{{ asset('storage/' . $photo->filename) }}" class="btn btn-success" download>Download</a>
            <a href="{{ route('photos.edit', $photo->id) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('photos.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Are you sure want to delete this photo?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-secondary">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection