<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Frame;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    // -------------------
    // C: Capture Collage 3x
    // -------------------
    public function create()
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login')->with('error', 'Silakan login dahulu.');

        $frames = Frame::where('active', true)->get();
        return view('photos.capture', compact('frames'));
    }

    public function store(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login')->with('error', 'Silakan login dahulu.');

        $request->validate([
            'image' => 'required|string',
            'frame_id' => 'nullable|exists:frames,id'
        ]);

        $this->saveImage($request->image, $request->frame_id, $userId, true);

        return redirect('/photos')->with('success', 'Collage berhasil disimpan!');
    }

    // -------------------
    // Helper: Save Image / Collage
    // -------------------
    private function saveImage($dataUri, $frameId, $userId, $isCollage = false)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
            $data = substr($dataUri, strpos($dataUri, ',') + 1);
            $type = strtolower($type[1]);
            if (!in_array($type, ['jpg','jpeg','png'])) throw new \Exception('Format file tidak didukung.');
            $data = base64_decode($data);
        } else {
            throw new \Exception('Invalid image data.');
        }

        $timestamp = time();
        $tmpPath = "tmp/{$userId}_{$timestamp}.{$type}";
        Storage::disk('public')->put($tmpPath, $data);

        $image = Image::make(storage_path("app/public/{$tmpPath}"));

        // Resize max width
        $maxWidth = 1280;
        if ($image->width() > $maxWidth) {
            $image->resize($maxWidth, null, function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Simpan ORIGINAL IMAGE (Mentah untuk diedit nanti)
        $originalPath = "photos/original_{$userId}_{$timestamp}.png";
        Storage::disk('public')->put($originalPath, (string)$image->encode('png', 90));

        // Proses Pemberian Frame
        $finalImage = clone $image; 
        if ($frameId) {
            $frame = Frame::find($frameId);
            if ($frame && Storage::disk('public')->exists($frame->image_path)) {
                $overlay = Image::make(storage_path('app/public/' . $frame->image_path));
                $numSlots = $isCollage ? 3 : 1; 
                $slotHeight = intval($finalImage->height() / $numSlots);

                for ($i=0; $i<$numSlots; $i++){
                    $slotOverlay = clone $overlay;
                    $slotOverlay->resize($finalImage->width(), $slotHeight, function($constraint){
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $finalImage->insert($slotOverlay, 'top-left', 0, $i*$slotHeight);
                }
            }
        }

        $suffix = $isCollage ? '_collage' : '';
        $fileName = "photos/{$userId}_{$timestamp}{$suffix}.png";
        Storage::disk('public')->put($fileName, (string)$finalImage->encode('png', 90));

        // Thumbnail
        $thumbName = "photos/thumbs/{$userId}_{$timestamp}{$suffix}.jpg";
        $thumbnail = clone $finalImage;
        $thumbnail->resize(300, null, function($constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        Storage::disk('public')->put($thumbName, (string)$thumbnail->encode('jpg', 85));

        Storage::disk('public')->delete($tmpPath);

        Photo::create([
            'user_id' => $userId,
            'filename' => $fileName,
            'thumb_path' => $thumbName,
            'frame_id' => $frameId,
            'original_filename' => $originalPath,
        ]);
    }

    // -------------------
    // R: Gallery
    // -------------------
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login')->with('error','Silakan login dahulu.');

        $photos = Photo::where('user_id', $userId)->orderBy('created_at','desc')->paginate(12);
        return view('photos.gallery', compact('photos'));
    }

    public function show($id)
    {
        $photo = Photo::findOrFail($id);
        return view('photos.show', compact('photo'));
    }

    // -------------------
    // U: Update Frame (MENGGANTI FOTO LAMA)
    // -------------------
    public function edit($id)
    {
        $photo = Photo::findOrFail($id);
        $frames = Frame::where('active', true)->get();
        return view('photos.edit', compact('photo','frames'));
    }

    public function update(Request $request, $id)
    {
        $photo = Photo::findOrFail($id);
        $request->validate(['frame_id'=>'nullable|exists:frames,id']);
        
        $originalPath = storage_path('app/public/' . $photo->original_filename);
        if (!file_exists($originalPath)) {
             return back()->with('error', 'File original tidak ditemukan.');
        }

        $image = Image::make($originalPath); 
        
        // Aplikasikan Frame Baru
        if($request->frame_id){ 
            $frame = Frame::find($request->frame_id);
            if($frame && Storage::disk('public')->exists($frame->image_path)){
                $overlay = Image::make(storage_path('app/public/' . $frame->image_path));
                $numSlots = 3; // Karena ini kolase
                $slotHeight = intval($image->height() / $numSlots);

                for($i=0;$i<$numSlots;$i++){
                    $slotOverlay = clone $overlay;
                    $slotOverlay->resize($image->width(), $slotHeight, function($constraint){
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $image->insert($slotOverlay,'top-left',0,$i*$slotHeight); 
                }
            }
        } 

        // 1. Hapus file hasil olahan lama (bukan original) agar tidak menumpuk
        if(Storage::disk('public')->exists($photo->filename)){
            Storage::disk('public')->delete($photo->filename);
        }
        if(Storage::disk('public')->exists($photo->thumb_path)){
            Storage::disk('public')->delete($photo->thumb_path);
        }

        // 2. Simpan file hasil olahan baru
        $timestamp = time();
        $fileName = "photos/{$photo->user_id}_{$timestamp}_edit.png";
        $thumbName = "photos/thumbs/{$photo->user_id}_{$timestamp}_edit.jpg";

        Storage::disk('public')->put($fileName, (string)$image->encode('png', 90));

        $thumbnail = clone $image;
        $thumbnail->resize(300, null, function($constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        Storage::disk('public')->put($thumbName, (string)$thumbnail->encode('jpg', 85));

        // 3. Update data yang sudah ada (BUKAN create baru)
        $photo->update([
            'filename' => $fileName,
            'thumb_path' => $thumbName,
            'frame_id' => $request->frame_id, 
        ]);

        return redirect('/photos')->with('success','Frame foto berhasil diperbarui!');
    }

    // -------------------
    // D: Delete (BERSIHKAN SEMUA FILE)
    // -------------------
    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);

        // Hapus semua file terkait di storage
        $filesToDelete = [$photo->filename, $photo->thumb_path, $photo->original_filename];
        
        foreach ($filesToDelete as $file) {
            if ($file && Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }

        $photo->delete();

        return redirect('/photos')->with('success','Foto dan semua file terkait berhasil dihapus!');
    }
}