@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto flex flex-col items-center">

        <!-- Title -->
        <div class="text-center mb-6">
            <h2 class="text-3xl font-[Poppins] font-bold text-white drop-shadow-md">Photobooth</h2>
            <p class="text-gray-300 text-sm">Select a frame, strike a pose!</p>
        </div>

        <!-- Main Capture Area -->
        <div class="relative w-full max-w-[720px] aspect-[4/3] bg-black rounded-3xl overflow-hidden shadow-2xl border-4 border-white/10 ring-1 ring-white/5 mb-6 group"
            id="captureBox">
            <div id="camera" class="w-full h-full object-cover"></div>
            <img id="overlay" class="absolute inset-0 w-full h-full pointer-events-none z-10 hidden" src="">

            <!-- Countdown Overlay -->
            <div id="countdown" class="absolute inset-0 flex items-center justify-center pointer-events-none z-50 hidden">
                <span class="text-9xl font-black text-white drop-shadow-[0_10px_10px_rgba(0,0,0,0.8)] animate-pulse"
                    id="countdownText">3</span>
            </div>

            <!-- Flash Effect -->
            <div id="flash"
                class="absolute inset-0 bg-white opacity-0 pointer-events-none z-50 transition-opacity duration-150"></div>
        </div>

        <!-- Result / Collage Preview Area (Hidden Initially) -->
        <div id="collage-preview" class="hidden flex-col items-center w-full max-w-[400px] animate-fade-in-up">
            <div
                class="bg-white p-3 rounded-lg shadow-2xl transform rotate-1 transition-transform hover:rotate-0 duration-300">
                <img id="collageImg" src="" class="w-full rounded h-auto block">
            </div>

            <div class="flex gap-4 mt-8 w-full">
                <button id="btnRetake"
                    class="flex-1 py-3 rounded-xl font-bold bg-gray-700 text-white hover:bg-gray-600 transition-all shadow-lg">
                    Retake
                </button>
                <button id="btnSave"
                    class="flex-1 py-3 rounded-xl font-bold bg-gradient-to-r from-pink-500 to-purple-600 text-white hover:from-pink-400 hover:to-purple-500 transition-all shadow-lg transform hover:-translate-y-1">
                    Save Photo
                </button>
            </div>
        </div>

        <!-- Controls Section (Frame Selection & Capture) -->
        <div id="controlsSection" class="w-full flex flex-col items-center transition-all duration-300">

            <!-- Frame Selector -->
            <div id="frameSection" class="w-full max-w-2xl mb-8">
                <div class="flex items-center justify-between mb-2 px-4">
                    <span class="text-sm font-bold text-gray-300 uppercase tracking-wider">Frames</span>
                    <button id="noFrame"
                        class="text-xs text-pink-400 hover:text-pink-300 font-semibold uppercase tracking-wide">Clear
                        Frame</button>
                </div>

                <div class="flex overflow-x-auto pb-4 gap-4 px-4 snap-x scrollbar-hide no-scrollbar" id="frameChooser">
                    @foreach($frames as $f)
                        <div class="flex-shrink-0 snap-center">
                            <button
                                class="frame-thumb relative w-20 h-20 rounded-xl overflow-hidden border-2 border-transparent hover:border-white/50 focus:outline-none transition-all group"
                                data-id="{{ $f->id }}" data-src="{{ asset('storage/' . $f->image_path) }}">
                                <img src="{{ asset('storage/' . $f->image_path) }}"
                                    class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity">
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-black/60 text-[9px] text-white text-center py-1 truncate">
                                    {{ $f->name }}
                                </div>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Capture Button -->
            <button id="btnCapture"
                class="group relative w-20 h-20 rounded-full border-4 border-white/20 flex items-center justify-center transition-all hover:scale-110 active:scale-95 shadow-xl hover:shadow-pink-500/20 mb-8">
                <div class="w-16 h-16 rounded-full bg-white group-hover:bg-pink-500 transition-colors duration-300"></div>
                <div class="absolute -inset-2 rounded-full border border-white/10 animate-ping opacity-20"></div>
            </button>

            <!-- Mini Previews (During capture process) -->
            <div class="mini-previews flex gap-3 h-16 opacity-50 justify-center" id="miniPreviews"></div>

        </div>

        <form id="saveForm" method="POST" action="{{ route('photos.store') }}">
            @csrf
            <input type="hidden" name="image" id="collageInput">
            <input type="hidden" name="frame_id" id="frameInput">
        </form>

    </div>

    <style>
        /* Custom Scrollbar Hide */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .frame-thumb.selected {
            border-color: #EC4899;
            /* Pink-500 */
            transform: scale(1.1);
            z-index: 10;
            box-shadow: 0 0 15px rgba(236, 72, 153, 0.5);
        }

        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.5s ease-out forwards;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script>
        // Logic Kamera & Capture (Dipertahankan tapi diadaptasi)
        Webcam.set({
            width: 720,
            height: 540,
            image_format: 'jpeg',
            jpeg_quality: 95,
            constraints: { video: true, facingMode: "user" }
        });
        Webcam.attach('#camera');

        let selectedFrame = null;
        const overlay = document.getElementById('overlay');
        const countdownEl = document.getElementById('countdown');
        const countdownText = document.getElementById('countdownText');
        const flash = document.getElementById('flash');
        const miniPreviews = document.getElementById('miniPreviews');
        const collagePreview = document.getElementById('collage-preview');
        const collageImg = document.getElementById('collageImg');
        const controlsSection = document.getElementById('controlsSection');
        const captureBox = document.getElementById('captureBox');
        const btnCapture = document.getElementById('btnCapture');

        function loadImage(src) {
            return new Promise(resolve => {
                if (!src) resolve(null);
                const img = new Image();
                img.onload = () => resolve(img);
                img.onerror = () => resolve(null);
                img.src = src;
            });
        }

        // Helper untuk merge frame ke 1 foto (untuk mini items)
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

        // Event Selector Frame
        document.querySelectorAll('.frame-thumb').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault(); // Prevent scroll jump
                document.querySelectorAll('.frame-thumb').forEach(i => i.classList.remove('selected'));
                item.classList.add('selected');

                selectedFrame = item.dataset.id;
                const src = item.dataset.src;

                overlay.src = src;
                overlay.classList.remove('hidden');
                document.getElementById('frameInput').value = selectedFrame;
            });
        });

        document.getElementById('noFrame').addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.frame-thumb').forEach(i => i.classList.remove('selected'));
            selectedFrame = null;
            overlay.classList.add('hidden');
            document.getElementById('frameInput').value = '';
        });

        async function countdownFn(seconds) {
            return new Promise(resolve => {
                countdownText.innerText = seconds;
                countdownEl.classList.remove('hidden');

                let interval = setInterval(() => {
                    seconds--;
                    if (seconds > 0) {
                        countdownText.innerText = seconds;
                    } else {
                        clearInterval(interval);
                        countdownEl.classList.add('hidden');
                        resolve();
                    }
                }, 1000);
            });
        }

        function snapPhoto() {
            return new Promise(resolve => {
                Webcam.snap(async data_uri => {
                    flash.style.opacity = 1;
                    setTimeout(() => { flash.style.opacity = 0; }, 200);
                    resolve(data_uri);
                });
            });
        }

        btnCapture.addEventListener('click', async () => {
            // UI Transitions
            controlsSection.classList.add('opacity-50', 'pointer-events-none'); // Disable controls interaction
            miniPreviews.innerHTML = '';

            let photos = [];
            const currentFrameId = selectedFrame;
            const currentFrameSrc = overlay.src.includes('storage') ? overlay.src : null;

            // AMBIL 3 CAPTURE
            for (let i = 0; i < 3; i++) {
                await countdownFn(3);
                const rawDataUri = await snapPhoto();
                photos.push(rawDataUri);

                // Buat mini thumbnail
                const miniImg = document.createElement('img');
                miniImg.className = 'w-12 h-16 object-cover rounded border border-white/50';

                if (currentFrameId && currentFrameSrc) {
                    const framedMiniUri = await mergeFrameToSinglePhoto(rawDataUri, currentFrameSrc);
                    miniImg.src = framedMiniUri;
                } else {
                    miniImg.src = rawDataUri;
                }
                miniPreviews.appendChild(miniImg);
            }

            // PROSES KOLASE HASIL
            const canvas = document.createElement('canvas');
            const imgObjs = [];
            for (let p of photos) {
                const img = await loadImage(p);
                imgObjs.push(img);
            }

            if (imgObjs.length > 0) {
                // Asumsi semua foto sama dimensinya
                canvas.width = imgObjs[0].width;
                canvas.height = imgObjs[0].height * 3;
                const ctx = canvas.getContext('2d');

                for (let i = 0; i < imgObjs.length; i++) {
                    ctx.drawImage(imgObjs[i], 0, imgObjs[0].height * i);
                }

                let previewDataUri = canvas.toDataURL('image/png');

                // Apply Frame ke Kolase Final
                if (currentFrameId && currentFrameSrc) {
                    const previewImage = await loadImage(previewDataUri);
                    const previewCanvas = document.createElement('canvas');
                    previewCanvas.width = previewImage.width;
                    previewCanvas.height = previewImage.height;
                    const previewCtx = previewCanvas.getContext('2d');
                    previewCtx.drawImage(previewImage, 0, 0);

                    const slotHeight = previewImage.height / 3;
                    // Frame ditempel 3 kali berulang ke bawah
                    const frameSlot = await loadImage(currentFrameSrc);

                    if (frameSlot) {
                        for (let i = 0; i < 3; i++) {
                            previewCtx.drawImage(frameSlot, 0, i * slotHeight, previewImage.width, slotHeight);
                        }
                    }
                    collageImg.src = previewCanvas.toDataURL('image/png');
                } else {
                    collageImg.src = previewDataUri;
                }

                document.getElementById('collageInput').value = collageImg.src;

                // Final UI Swith
                captureBox.classList.add('hidden');
                controlsSection.classList.add('hidden');
                collagePreview.classList.remove('hidden');
            }

            controlsSection.classList.remove('opacity-50', 'pointer-events-none');
        });

        // FUNGSI RETAKE / SAVE
        document.getElementById('btnRetake').addEventListener('click', () => {
            collagePreview.classList.add('hidden');
            miniPreviews.innerHTML = '';

            captureBox.classList.remove('hidden');
            controlsSection.classList.remove('hidden');
        });

        document.getElementById('btnSave').addEventListener('click', (e) => {
            const btn = e.target;
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
            btn.disabled = true;
            document.getElementById('saveForm').submit();
        });
    </script>
@endpush