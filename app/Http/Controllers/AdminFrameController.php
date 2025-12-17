<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frame;
use Illuminate\Support\Facades\Storage;

class AdminFrameController extends Controller
{
    // halaman admin index (list frames + upload)
    public function index()
    {
        $frames = Frame::all();
        return view('admin.frames.index', compact('frames'));
    }

    // proses upload frame
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        $path = $request->file('image')->store('frames', 'public');

        Frame::create([
            'name' => $request->name,
            'image_path' => $path,
            'active' => true,
        ]);

        return redirect()->route('admin.frames.index')->with('success', 'Frame berhasil ditambahkan!');
    }

    // [KODE BARU: Menampilkan Form Edit]
    public function edit($id)
    {
        $frame = Frame::findOrFail($id);
        return view('admin.frames.edit', compact('frame'));
    }

    // [KODE BARU: Menyimpan Perubahan]
    public function update(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // 2. Cari dan Update frame
        $frame = Frame::findOrFail($id);
        $frame->name = $request->name;
        $frame->save();

        // 3. Redirect
        return redirect()->route('admin.frames.index')->with('success', 'Nama frame **' . $frame->name . '** berhasil diperbarui!');
    }


    // delete frame
    public function destroy($id)
    {
        $frame = Frame::findOrFail($id);

        // hapus file fisik
        if (Storage::disk('public')->exists($frame->image_path)) {
            Storage::disk('public')->delete($frame->image_path);
        }

        // hapus database
        $frame->delete();

        return redirect()->route('admin.frames.index')->with('success', 'Frame berhasil dihapus!');
    }
}