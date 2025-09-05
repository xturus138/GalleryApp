<?php

namespace App\Http\Controllers;

use App\Helpers\SizeHelper;
use App\Models\Asset;
use App\Models\AssetHeart;
use App\Models\Comment;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function index()
    {
        $folders = Folder::where('created_by', Auth::id())->get();
        $totalStorageUsed = Asset::where('uploaded_by', Auth::id())->sum('file_size');
        $totalStorageFormatted = SizeHelper::formatSize($totalStorageUsed);
        $totalStorageLimit = '8 GB'; // Hardcoded
        $totalStorageLimitBytes = 8 * 1000 * 1000 * 1000; // 8GB in bytes
        $percentageUsed = $totalStorageUsed > 0 ? round(($totalStorageUsed / $totalStorageLimitBytes) * 100, 2) : 0;
        return view('dashboard.gallery', compact('folders', 'totalStorageFormatted', 'totalStorageLimit', 'percentageUsed'));
    }

    public function getAssets(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 21); // default 21 items per page
        $assets = Asset::where('uploaded_by', Auth::id())
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        $assets->getCollection()->transform(function ($asset) {
            $asset->formatted_size = SizeHelper::formatSize($asset->file_size);
            $asset->likes_count = $asset->hearts()->count();
            $asset->is_liked_by_user = $asset->isLikedBy(Auth::user());
            return $asset;
        });

        return response()->json($assets);
    }

    // Metode baru untuk mengambil aset berdasarkan folder dengan pagination
    public function getAssetsByFolder(Request $request, $folderId)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 21); // default 21 items per page
        $assets = Asset::where('uploaded_by', Auth::id())
            ->where('folder_id', $folderId)
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        $assets->getCollection()->transform(function ($asset) {
            $asset->formatted_size = SizeHelper::formatSize($asset->file_size);
            $asset->likes_count = $asset->hearts()->count();
            $asset->is_liked_by_user = $asset->isLikedBy(Auth::user());
            return $asset;
        });

        return response()->json($assets);
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'files' => 'required|array',
                'files.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:100000',
                'title' => 'nullable|string|max:255',
                'caption' => 'nullable|string',
                'folder' => [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) {
                        if ($value !== 'none' && !\App\Models\Folder::where('id', $value)->exists()) {
                            $fail('The selected folder is invalid.');
                        }
                    }
                ],
            ]);

            $uploadedAssets = [];
            $caption = $request->input('caption');
            $title = $request->input('title');
            $folderId = $request->input('folder');

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
                    'title' => $title,
                    'file_type' => $fileType,
                    'file_size' => $fileSize,
                    'blob_url' => Storage::url($fullPath),
                    'uploaded_by' => Auth::id(),
                    'caption' => $caption,
                    'folder_id' => ($folderId === 'none') ? null : $folderId,
                ]);

                $uploadedAssets[] = $asset;
            }

            return response()->json(['success' => true, 'assets' => $uploadedAssets], 200);
        } catch (\Exception $e) {
            \Log::error('Upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengunggah file.'], 500);
        }
    }

    public function show($id)
    {
        $asset = Asset::where('id', $id)->where('uploaded_by', Auth::id())->first();

        if (!$asset) {
            return response()->json(['error' => 'Asset not found'], 404);
        }

        return response()->json($asset);
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::where('id', $id)->where('uploaded_by', Auth::id())->first();

        if (!$asset) {
            return response()->json(['error' => 'Asset not found'], 404);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
            'folder_id' => 'nullable|uuid|exists:folders,id',
        ]);

        $asset->update($request->only(['title', 'caption', 'folder_id']));

        return response()->json(['success' => true, 'asset' => $asset]);
    }

    public function destroy($id)
    {
        $asset = Asset::where('id', $id)->where('uploaded_by', Auth::id())->first();

        if (!$asset) {
            return response()->json(['error' => 'Asset not found'], 404);
        }

        // Delete the file from storage
        Storage::disk('public')->delete(str_replace('/storage/', '', $asset->blob_url));

        $asset->delete();

        return response()->json(['success' => true, 'message' => 'Asset deleted successfully']);
    }

    public function like($id)
    {
        $asset = Asset::where('id', $id)->where('uploaded_by', Auth::id())->first();

        if (!$asset) {
            return response()->json(['error' => 'Asset not found'], 404);
        }

        $existingLike = AssetHeart::where('asset_id', $id)->where('user_id', Auth::id())->first();

        if ($existingLike) {
            return response()->json(['error' => 'Already liked'], 400);
        }

        AssetHeart::create([
            'asset_id' => $id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'likes_count' => $asset->fresh()->likes_count]);
    }

    public function unlike($id)
    {
        $asset = Asset::where('id', $id)->where('uploaded_by', Auth::id())->first();

        if (!$asset) {
            return response()->json(['error' => 'Asset not found'], 404);
        }

        $like = AssetHeart::where('asset_id', $id)->where('user_id', Auth::id())->first();

        if (!$like) {
            return response()->json(['error' => 'Not liked yet'], 400);
        }

        $like->delete();

        return response()->json(['success' => true, 'likes_count' => $asset->fresh()->likes_count]);
    }

    public function getComments($id)
    {
        \Log::info('getComments called with ID: ' . $id);
        \Log::info('Current user ID: ' . Auth::id());

        $asset = Asset::find($id);

        if (!$asset) {
            \Log::error('Asset not found for ID: ' . $id);
            return response()->json(['error' => 'Asset not found'], 404);
        }

        $comments = $asset->comments()->with('user')->oldest()->get();
        \Log::info('Found ' . $comments->count() . ' comments');

        return response()->json($comments);
    }

    public function storeComment(Request $request, $id)
    {
        \Log::info('storeComment called with ID: ' . $id);
        \Log::info('Current user ID: ' . Auth::id());
        \Log::info('Request data: ', $request->all());

        $asset = Asset::find($id);

        if (!$asset) {
            \Log::error('Asset not found for ID: ' . $id);
            return response()->json(['error' => 'Asset not found'], 404);
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'asset_id' => $id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        $comment->load('user');
        \Log::info('Comment created successfully');

        return response()->json(['success' => true, 'comment' => $comment]);
    }
}