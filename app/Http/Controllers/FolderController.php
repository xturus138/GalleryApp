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

    public function list()
    {
        $folders = Folder::where('created_by', Auth::id())->get();
        return response()->json($folders);
    }
}