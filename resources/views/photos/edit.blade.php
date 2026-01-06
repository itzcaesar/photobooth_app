@extends('layouts.app')

@section('content')
<style>
    /* Ukuran slot diperkecil agar pas di layar (Compact) */
    .photo-slot { 
        transition: all 0.3s ease; 
        border: 2px solid #ddd; 
        background: #000; 
        position: relative;
        width: 100%;
        max-width: 260px; /* Batas lebar agar tidak terlalu tinggi */
        aspect-ratio: 4 / 3; 
        overflow: hidden;
        border-radius: 6px;
        margin: 0 auto;
    }
    
    .photo-slot img.main-photo {
        position: absolute;
        width: 100%;
        height: 300%; 
        left: 0;
        object-fit: cover;
    }

    .frame-thumb {
        width: 70px;
        height: 55px;
        object-fit: contain;
        cursor: pointer;
        border: 2px solid #eee;
        border-radius: 4px;
        transition: 0.2s;
    }

    .frame-thumb.selected {
        border-color: #0d6efd;
        transform: scale(1.1);
    }

    .btn-xs {
        padding: 1px 6px;
        font-size: 12px;
    }
</style>

<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-md-4 text-center">
            <h6 class="fw-bold mb-3">Reorder & Preview</h6>
            
            <div id="photoOrderContainer" class="mb-3">
                @for($i=0; $i<3; $i++)
                <div class="photo-slot mb-2 shadow-sm" data-index="{{ $i }}">
                    <img src="{{ asset('storage/' . $photo->original_filename) }}" 
                         class="main-photo"
                         style="top: -{{ $i * 100 }}%;">
                    
                    <img class="overlay-frame-preview" src="" 
                         style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; object-fit:contain; z-index: 10;">

                    <div class="controls" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); display: flex; flex-direction: column; gap: 4px; z-index: 20;">
                        <button type="button" class="btn btn-dark btn-xs move-up">▲</button>
                        <button type="button" class="btn btn-dark btn-xs move-down">▼</button>
                    </div>
                </div>
                @endfor
            </div>

            <form id="updateForm" action="{{ route('photos.update', $photo->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="frame_id" id="frameInput" value="{{ $photo->frame_id }}">
                <input type="hidden" name="photo_order" id="orderInput" value="0,1,2">
                <button type="submit" class="btn btn-primary w-100 shadow-sm">Save Changes</button>
            </form>
        </div>

        <div class="col-md-3 ms-md-4 mt-4 mt-md-0">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body p-3 text-center">
                    <h6 class="fw-bold mb-3">Frames</h6>
                    <div id="framesList" class="d-flex flex-wrap gap-2 justify-content-center">
                        <button type="button" id="noFrame" class="btn btn-sm btn-outline-danger w-100 mb-2">Remove Frame</button>
                        @foreach($frames as $f)
                        <img src="{{ asset('storage/'.$f->image_path) }}" 
                             data-id="{{ $f->id }}" 
                             class="frame-thumb"
                             title="{{ $f->name }}">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('photos.index') }}" class="text-muted small">← Back to Gallery</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const container = document.getElementById('photoOrderContainer');
    const orderInput = document.getElementById('orderInput');
    const frameInput = document.getElementById('frameInput');

    function updateOrderValue() {
        const slots = document.querySelectorAll('.photo-slot');
        orderInput.value = Array.from(slots).map(s => s.dataset.index).join(',');
    }

    container.addEventListener('click', (e) => {
        const slot = e.target.closest('.photo-slot');
        if (!slot) return;
        if (e.target.classList.contains('move-up') && slot.previousElementSibling) {
            container.insertBefore(slot, slot.previousElementSibling);
        } else if (e.target.classList.contains('move-down') && slot.nextElementSibling) {
            container.insertBefore(slot.nextElementSibling, slot);
        }
        updateOrderValue();
    });

    function applyFramePreview(src) {
        const overlays = document.querySelectorAll('.overlay-frame-preview');
        overlays.forEach(img => {
            img.src = src || '';
            img.style.display = src ? 'block' : 'none';
        });
    }

    window.addEventListener('DOMContentLoaded', () => {
        if (frameInput.value) {
            const img = document.querySelector(`.frame-thumb[data-id="${frameInput.value}"]`);
            if (img) { img.classList.add('selected'); applyFramePreview(img.src); }
        }
    });

    document.querySelectorAll('.frame-thumb').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.frame-thumb').forEach(i => i.classList.remove('selected'));
            el.classList.add('selected');
            frameInput.value = el.dataset.id;
            applyFramePreview(el.src);
        });
    });

    document.getElementById('noFrame').addEventListener('click', () => {
        document.querySelectorAll('.frame-thumb').forEach(i => i.classList.remove('selected'));
        frameInput.value = '';
        applyFramePreview(null);
    });
</script>
@endpush