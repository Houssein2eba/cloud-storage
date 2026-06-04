<?php

namespace App\Http\Controllers;

use App\Models\CloudFolder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        if (!empty($data['parent_id'])) {
            CloudFolder::where('user_id', auth()->id())
                ->where('id', $data['parent_id'])
                ->firstOrFail();
        }

        CloudFolder::create([
            'user_id' => auth()->id(),
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
        ]);

        return back();
    }

    public function destroy(CloudFolder $folder)
    {
        abort_unless($folder->user_id === auth()->id(), 403);

        $folder->delete();

        return back();
    }
}