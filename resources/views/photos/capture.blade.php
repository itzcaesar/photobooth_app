@extends('layouts.app')

@section('content')
<style>
/* ---------------------------------- */
/* STYLE UNTUK FUNGSI CAPTURE */
/* ---------------------------------- */
.capture-wrapper {
    position: relative;
    width: 100%;
    max-width: 720px;
    aspect-ratio: 4 / 3;
    background: #000;
    overflow: hidden;
    border-radius: 8px;
    margin: 0 auto;
}

#camera video,
#camera canvas {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}

.overlay-frame {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

#countdown {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 80px;
    color: white;
    font-weight: bold;
    text-shadow: 3px 3px 10px rgba(0,0,0,0.8);
    display: none;
    pointer-events: none;
    z-index: 100;
}

#flash {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: white;
    opacity: 0;
    pointer-events: none;
    z-index: 999;
}

/* ---------------------------------- */
/* PREVIEW KOLASE (DIKEATASIN) */
/* ---------------------------------- */
#collage-preview {
    display: none;
    margin: 0 auto; /* Hilangkan margin atas berlebih */
    max-width: 240px; 
    padding-top: 5px;
}

#collage-preview img {
    width: 100%;
    height: auto; 
    display: block;
    /* border-radius: 8px; */
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    /* border: 4px solid #fff; */
}

.mini-previews {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 15px 0;
}

.mini-previews img {
    width: 100px;
    height: auto;
    border-radius: 4px;
    border: 2px solid #ccc;
}

.frame-thumb {
    width: 80px;
    border: 2px solid transparent;
    cursor: pointer;
    border-radius: 6px;
    transition: 0.2s;
}

.frame-thumb.selected {
    border-color: #0d6efd;
    transform: scale(1.05);
}

#frameChooser {
    justify-content: center;
    margin-bottom: 20px;
}
</style>

<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">

            <div class="capture-wrapper mb-3" id="captureBox">
                <div id="camera"></div>
                <img id="overlay" class="overlay-frame" src="" style="display:none;">
                <div id="countdown"></div>
                <div id="flash"></div>
            </div>

            <div id="collage-preview" class="mb-4">
                <h5 class="mb-2">Your Result</h5>
                <img id="collageImg" src="">
            </div>

            <div id="frameSection">
                <h5>Choose Frame</h5>
                <div id="frameChooser" class="d-flex flex-wrap mb-3">
                    <button id="noFrame" class="btn btn-sm btn-outline-secondary mb-2 me-2">No Frame</button>
                    @foreach($frames as $f)
                    <div class="me-2 mb-3 text-center">
                        <img src="{{ asset('storage/'.$f->image_path) }}" data-id="{{ $f->id }}" class="frame-thumb">
                        <div style="font-size:11px; margin-top:5px;">{{ $f->name }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mini-previews" id="miniPreviews"></div>

            <div class="mt-2">
                <button id="btnCapture" class="btn btn-success btn-lg px-5">Capture</button>
                <button id="btnRetake" class="btn btn-secondary" style="display:none;">Retake</button>
                <button id="btnSave" class="btn btn-primary" style="display:none;">Save to Gallery</button>
            </div>

            <form id="saveForm" method="POST" action="{{ route('photos.store') }}">
                @csrf
                <input type="hidden" name="image" id="collageInput">
                <input type="hidden" name="frame_id" id="frameInput">
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
Webcam.set({
    width: 720,
    height: 540,
    image_format: 'jpeg',
    jpeg_quality: 92,
    constraints: { video: true }
});
Webcam.attach('#camera');

let selectedFrame = null;
const overlay = document.getElementById('overlay');
const countdownEl = document.getElementById('countdown');
const flash = document.getElementById('flash');
const miniPreviews = document.getElementById('miniPreviews');
const collagePreview = document.getElementById('collage-preview');
const collageImg = document.getElementById('collageImg');
const frameSection = document.getElementById('frameSection');
const captureBox = document.getElementById('captureBox');
const btnCapture = document.getElementById('btnCapture');

function loadImage(src){
    return new Promise(resolve=>{
        if(!src) resolve(null);
        const img = new Image();
        img.onload = () => resolve(img);
        img.src = src;
    });
}

async function mergeFrameToSinglePhoto(photoDataUri, frameSrc) {
    const img = await loadImage(photoDataUri);
    const frameImg = await loadImage(frameSrc);
    if (!frameImg) return photoDataUri;

    const canvas = document.createElement('canvas');
    canvas.width = img.width;
    canvas.height = img.height;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(img, 0, 0);
    ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height); 
    return canvas.toDataURL('image/png');
}

// Event Pilihan Frame
document.querySelectorAll('.frame-thumb').forEach(item=>{
    item.addEventListener('click',()=>{
        document.querySelectorAll('.frame-thumb').forEach(i=>i.classList.remove('selected'));
        item.classList.add('selected');
        selectedFrame = item.dataset.id;
        overlay.src = item.src;
        overlay.style.display = 'block';
        document.getElementById('frameInput').value = selectedFrame;
    });
});

document.getElementById('noFrame').addEventListener('click',()=>{
    document.querySelectorAll('.frame-thumb').forEach(i=>i.classList.remove('selected'));
    selectedFrame = null;
    overlay.style.display = 'none';
    document.getElementById('frameInput').value = '';
});

async function countdown(seconds){
    return new Promise(resolve=>{
        countdownEl.innerText = seconds;
        countdownEl.style.display = 'block';
        let interval = setInterval(()=>{
            seconds--;
            if(seconds > 0) countdownEl.innerText = seconds;
            else {
                clearInterval(interval);
                countdownEl.style.display = 'none';
                resolve();
            }
        }, 1000);
    });
}

function snapPhoto(){
    return new Promise(resolve=>{
        Webcam.snap(async data_uri=>{
            flash.style.opacity = 1;
            setTimeout(() => { flash.style.opacity = 0; }, 150);
            resolve(data_uri); 
        });
    });
}

// --- FUNGSI CAPTURE ---
btnCapture.addEventListener('click', async () => {
    // 1. LANGSUNG SEMBUNYIKAN PILIHAN FRAME & TOMBOL CAPTURE
    frameSection.style.display = 'none';
    btnCapture.style.display = 'none';
    
    miniPreviews.innerHTML = '';
    collagePreview.style.display = 'none';
    let photos = [];
    
    const currentFrameId = selectedFrame;
    const currentFrameSrc = overlay.src;

    // Proses ambil 3 foto
    for(let i=0; i<3; i++){
        await countdown(3);
        const rawDataUri = await snapPhoto();
        photos.push(rawDataUri);

        const miniImg = document.createElement('img');
        if (currentFrameId && currentFrameSrc) {
            const framedMiniUri = await mergeFrameToSinglePhoto(rawDataUri, currentFrameSrc);
            miniImg.src = framedMiniUri;
        } else {
            miniImg.src = rawDataUri;
        }
        miniPreviews.appendChild(miniImg);
    }

    // Gabung foto jadi kolase
    const canvas = document.createElement('canvas');
    const imgObjs = [];
    for(let p of photos){
        const img = new Image();
        img.src = p;
        imgObjs.push(img);
        await new Promise(r => img.onload = r);
    }

    canvas.width = imgObjs[0].width;
    canvas.height = imgObjs[0].height * 3;
    const ctx = canvas.getContext('2d');

    for(let i=0; i<imgObjs.length; i++){
        ctx.drawImage(imgObjs[i], 0, imgObjs[0].height * i);
    }
    
    const previewDataUri = canvas.toDataURL('image/png'); 

    // Tambahkan frame pada hasil preview akhir
    if (currentFrameId && currentFrameSrc) {
        const previewImage = await loadImage(previewDataUri);
        const previewCanvas = document.createElement('canvas');
        previewCanvas.width = previewImage.width;
        previewCanvas.height = previewImage.height;
        const previewCtx = previewCanvas.getContext('2d');
        previewCtx.drawImage(previewImage, 0, 0);
        
        const slotHeight = previewImage.height / 3;
        for(let i=0; i<3; i++){
            const frameSlot = await loadImage(currentFrameSrc);
            previewCtx.drawImage(frameSlot, 0, i * slotHeight, previewImage.width, slotHeight);
        }
        collageImg.src = previewCanvas.toDataURL('image/png');
    } else {
        collageImg.src = previewDataUri;
    }

    // 2. TAMPILKAN HASIL & SEMBUNYIKAN AREA KAMERA
    captureBox.style.display = 'none';
    miniPreviews.style.display = 'none';
    collagePreview.style.display = 'block';

    document.getElementById('collageInput').value = previewDataUri; 
    document.getElementById('btnRetake').style.display = 'inline-block';
    document.getElementById('btnSave').style.display = 'inline-block';
});

// --- FUNGSI RETAKE ---
document.getElementById('btnRetake').addEventListener('click', () => {
    miniPreviews.innerHTML = '';
    collagePreview.style.display = 'none';
    miniPreviews.style.display = 'flex';
    
    // Kembalikan area kamera dan frame chooser
    captureBox.style.display = 'block';
    frameSection.style.display = 'block';
    btnCapture.style.display = 'inline-block';
    
    document.getElementById('btnRetake').style.display = 'none';
    document.getElementById('btnSave').style.display = 'none';
});

document.getElementById('btnSave').addEventListener('click', () => {
    document.getElementById('saveForm').submit();
});
</script>
@endpush