<?php

namespace App\Http\Controllers;

use App\Models\CloudFolder;
use App\Models\CloudFile;
use Illuminate\Http\Request;

class CloudController extends Controller
{
    public function index(Request $request)
    {
        // this method will display the main cloud interface with folders and files
        $user = auth()->user();
        $folderId = $request->query('folder');

        $currentFolder = null;
// if folderId is provided, we need to load that folder and its contents
        if ($folderId) {
            $currentFolder = CloudFolder::where('user_id', $user->id)
                ->where('id', $folderId)
                ->firstOrFail();
        }
// load folders and files in the current folder
        $folders = CloudFolder::where('user_id', $user->id)
            ->where('parent_id', $folderId)
            ->orderBy('name')
            ->get();
// we also want to load files in the current folder
        $files = CloudFile::where('user_id', $user->id)
            ->where('folder_id', $folderId)
            ->orderBy('original_name')
            ->get();
//
        $tree = CloudFolder::where('user_id', $user->id)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return view('cloud.index', compact(
            'folders',
            'files',
            'currentFolder',
            'tree'
        ));
    }
}