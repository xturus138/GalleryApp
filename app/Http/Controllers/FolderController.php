<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Folder;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    public function create(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $folder = Folder::create([
            'id' => Str::uuid(),
            'name' => $request->input('name'),
            'created_by' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'folder' => $folder], 201);
    }

    public function list(Request $request)
    {
        $page = $request->query('page', 1);
        $folders = Folder::where('created_by', Auth::id())->withCount('assets')->paginate(5, ['*'], 'page', $page);
        return response()->json($folders);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $folder = Folder::where('id', $id)->where('created_by', Auth::id())->first();

        if (!$folder) {
            return response()->json(['success' => false, 'message' => 'Folder not found'], 404);
        }

        $folder->update(['name' => $request->input('name')]);

        return response()->json(['success' => true, 'folder' => $folder], 200);
    }

    public function destroy($id)
    {
        $folder = Folder::where('id', $id)->where('created_by', Auth::id())->first();

        if (!$folder) {
            return response()->json(['success' => false, 'message' => 'Folder not found'], 404);
        }

        // Handle assets in the folder by setting folder_id to null
        \App\Models\Asset::where('folder_id', $id)->update(['folder_id' => null]);

        $folder->delete();

        return response()->json(['success' => true, 'message' => 'Folder deleted successfully'], 200);
    }
}
