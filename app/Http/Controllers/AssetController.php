<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Asset;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function index()
    {
        return view('dashboard.gallery');
    }

    public function getAssets()
    {
        $assets = Asset::where('uploaded_by', Auth::id())
            ->latest()
            ->get();

        return response()->json($assets);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:100000',
        ]);

        $uploadedAssets = [];
        $caption = $request->input('caption');
        $title = $request->input('title'); // Ambil input 'title'

        foreach ($request->file('files') as $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($originalName) . '-' . time() . '.' . $extension;

            $path = 'uploads/' . Auth::id();
            $fullPath = $file->storeAs($path, $filename, 'public');

            $fileSize = $file->getSize();
            $fileType = $file->getClientMimeType();

            $asset = Asset::create([
                'id' => Str::uuid(),
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'title' => $title, // Simpan title ke database
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'blob_url' => Storage::url($fullPath),
                'uploaded_by' => Auth::id(),
                'caption' => $caption,
            ]);

            $uploadedAssets[] = $asset;
        }

        return response()->json(['success' => true, 'assets' => $uploadedAssets], 200);
    }
}