@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h4>Edit</h4>

        {{-- Pembatasan Tinggi Maksimum untuk Preview --}}
        <div class="photo-wrapper mb-3" style="position:relative; max-width:720px; max-height: 80vh; overflow: hidden; margin: 0 auto;">
            
            {{-- Menggunakan original_filename sebagai dasar pengeditan --}}
            <img src="{{ asset('storage/' . $photo->original_filename) }}" 
                 id="photoPreview" 
                 style="
                     width:100%; 
                     display:block; 
                     object-fit:contain;
                     max-height: 80vh; 
                     height: auto;
                 ">

            <div id="overlayWrapper" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none;">
                @for($i=0;$i<3;$i++)
                <img class="overlay-frame" src="" style="display:none; position:absolute; top:{{ $i*33.3333 }}%; left:0; width:100%; height:33.3333%; object-fit:contain;">
                @endfor
            </div>
        </div>

        <form id="updateForm" action="{{ route('photos.update', $photo->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="frame_id" id="frameInput" value="{{ $photo->frame_id }}">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            
            {{-- TOMBOL DOWNLOAD DIHILANGKAN UNTUK MENGHINDARI KERANCUAN --}}
            {{-- <a href="{{ asset('storage/'.$photo->filename) }}" class="btn btn-outline-primary" download>Download Current</a> --}}
        </form>

        <p>Taken at: {{ $photo->created_at }}</p>
    </div>

    <div class="col-md-4">
        <h5>Choose Frame</h5>
        <div id="framesList" class="d-flex flex-wrap">
            <button type="button" id="noFrame" class="btn btn-outline-secondary mb-2 me-2">No Frame</button>
            @foreach($frames as $f)
            <img src="{{ asset('storage/'.$f->image_path) }}" data-id="{{ $f->id }}" class="frame-thumb mb-2 me-2" style="width:80px; cursor:pointer;" title="{{ $f->name }}">
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedFrame = {{ $photo->frame_id ?? 'null' }};
const overlayFrames = document.querySelectorAll('#overlayWrapper .overlay-frame');

function updateOverlayFrame(src){
    overlayFrames.forEach(img=>{
        if(src){ 
            img.src=src; 
            img.style.display='block'; 
        }
        else{ 
            img.src=''; 
            img.style.display='none'; 
        }
    });
}

window.addEventListener('DOMContentLoaded',()=>{
    if(selectedFrame){
        const frameImg = document.querySelector(`.frame-thumb[data-id='${selectedFrame}']`);
        if(frameImg) updateOverlayFrame(frameImg.src);
    }
});

document.querySelectorAll('.frame-thumb').forEach(el=>{
    el.addEventListener('click',()=>{
        document.querySelectorAll('.frame-thumb').forEach(i=>i.classList.remove('selected'));
        el.classList.add('selected');
        selectedFrame = el.dataset.id;
        document.getElementById('frameInput').value = selectedFrame;
        updateOverlayFrame(el.src); 
    });
});

document.getElementById('noFrame').addEventListener('click',()=>{
    document.querySelectorAll('.frame-thumb').forEach(i=>i.classList.remove('selected'));
    selectedFrame = null;
    document.getElementById('frameInput').value='';
    updateOverlayFrame(null); 
});
</script>
@endpush