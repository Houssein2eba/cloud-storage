<?php

namespace App\Http\Controllers;

use App\Models\CloudFile;
use App\Models\CloudFolder;

class TrashController extends Controller
{
    public function index()
    {
        $files = CloudFile::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $folders = CloudFolder::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('cloud.trash', compact('files', 'folders'));
    }

    public function restoreFile($id)
    {
        $file = CloudFile::onlyTrashed()
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $file->restore();

        return back();
    }

    public function forceDeleteFile($id)
    {
        $file = CloudFile::onlyTrashed()
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $file->forceDelete();

        return back();
    }

    public function restoreFolder($id)
    {
        $folder = CloudFolder::onlyTrashed()
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $folder->restore();

        return back();
    }
}