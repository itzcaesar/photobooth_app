<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Frame;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login')->with('error','Silakan login dahulu.');
        $photos = Photo::where('user_id', $userId)->orderBy('created_at','desc')->paginate(12);
        return view('photos.index', compact('photos'));
    }

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

    private function saveImage($dataUri, $frameId, $userId, $isCollage = false)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
            $data = base64_decode(substr($dataUri, strpos($dataUri, ',') + 1));
        } else {
            throw new \Exception('Invalid image data.');
        }

        $timestamp = time();
        $originalPath = "photos/original_{$userId}_{$timestamp}.png";
        $image = Image::make($data);
        
        // Resize standar 720px width
        if ($image->width() > 720) {
            $image->resize(720, null, function($c){ $c->aspectRatio(); $c->upsize(); });
        }

        Storage::disk('public')->put($originalPath, (string)$image->encode('png', 90));
        $this->processFinalImage($image, $frameId, $userId, $timestamp, $originalPath);
    }

    public function edit($id)
    {
        $photo = Photo::findOrFail($id);
        $frames = Frame::where('active', true)->get();
        return view('photos.edit', compact('photo','frames'));
    }

    public function update(Request $request, $id)
    {
        $photo = Photo::findOrFail($id);
        $request->validate([
            'frame_id' => 'nullable|exists:frames,id',
            'photo_order' => 'nullable|string' 
        ]);
        
        $originalPath = storage_path('app/public/' . $photo->original_filename);
        if (!file_exists($originalPath)) return back()->with('error', 'File tidak ditemukan.');

        $image = Image::make($originalPath); 
        $width = $image->width();
        $height = $image->height();
        $slotHeight = intval($height / 3);

        // REORDER LOGIC
        if ($request->photo_order && $request->photo_order !== "0,1,2") {
            $order = explode(',', $request->photo_order);
            $slices = [];
            for ($i = 0; $i < 3; $i++) {
                $slices[$i] = clone $image;
                $slices[$i]->crop($width, $slotHeight, 0, $i * $slotHeight);
            }

            $image = Image::canvas($width, $slotHeight * 3);
            foreach ($order as $newPos => $originalPos) {
                $image->insert($slices[$originalPos], 'top-left', 0, $newPos * $slotHeight);
            }
            Storage::disk('public')->put($photo->original_filename, (string)$image->encode('png', 90));
        }

        // Cleanup & Re-process
        if (Storage::disk('public')->exists($photo->filename)) Storage::disk('public')->delete($photo->filename);
        if (Storage::disk('public')->exists($photo->thumb_path)) Storage::disk('public')->delete($photo->thumb_path);

        $this->processFinalImage($image, $request->frame_id, $photo->user_id, time(), $photo->original_filename, $photo);

        return redirect('/photos')->with('success', 'Perubahan berhasil disimpan!');
    }

    private function processFinalImage($image, $frameId, $userId, $timestamp, $originalPath, $photoModel = null)
    {
        $width = $image->width();
        $slotHeight = intval($image->height() / 3);
        $finalImage = clone $image;

        if ($frameId) {
            $frame = Frame::find($frameId);
            if ($frame && Storage::disk('public')->exists($frame->image_path)) {
                $overlay = Image::make(storage_path('app/public/' . $frame->image_path));
                for ($i=0; $i<3; $i++){
                    $slotOverlay = clone $overlay;
                    $slotOverlay->resize($width, $slotHeight, function($c){ $c->aspectRatio(); $c->upsize(); });
                    $finalImage->insert($slotOverlay, 'top-left', 0, $i * $slotHeight);
                }
            }
        }

        $fileName = "photos/{$userId}_{$timestamp}_final.png";
        $thumbName = "photos/thumbs/{$userId}_{$timestamp}_thumb.jpg";

        Storage::disk('public')->put($fileName, (string)$finalImage->encode('png', 90));
        
        $thumbnail = clone $finalImage;
        $thumbnail->resize(300, null, function($c){ $c->aspectRatio(); $c->upsize(); });
        Storage::disk('public')->put($thumbName, (string)$thumbnail->encode('jpg', 85));

        if ($photoModel) {
            $photoModel->update(['filename' => $fileName, 'thumb_path' => $thumbName, 'frame_id' => $frameId]);
        } else {
            Photo::create(['user_id' => $userId, 'filename' => $fileName, 'thumb_path' => $thumbName, 'frame_id' => $frameId, 'original_filename' => $originalPath]);
        }
    }

    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);
        Storage::disk('public')->delete([$photo->filename, $photo->thumb_path, $photo->original_filename]);
        $photo->delete();
        return redirect('/photos')->with('success', 'Foto dihapus!');
    }
}