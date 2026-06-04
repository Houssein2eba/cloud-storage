<?php

namespace App\Http\Controllers;

use App\Models\CloudFile;
use App\Models\CloudFolder;
use App\Models\FileVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        // validate the incoming request
        $data = $request->validate([
            'file' => ['required', 'file', 'max:2097152'],
            'folder_id' => ['nullable', 'integer'],
        ]);
// we need to check if the folder_id is valid and belongs to the user
        $user = auth()->user();
        $folderId = $data['folder_id'] ?? null;
// if folderId is provided, we need to load that folder and ensure it belongs to the user
        if ($folderId) {
            CloudFolder::where('user_id', $user->id)
                ->where('id', $folderId)
                ->firstOrFail();
        }

            $uploaded = $request->file('file');
//we are generating a unique stored name for the file to avoid conflicts and ensure security
        $storedName = Str::uuid() . '.' . $uploaded->getClientOriginalExtension();
       
// we are storing the file in the local storage under a directory named after the user id
Storage::disk('local')->putFileAs(
    "cloud/{$user->id}",
    $uploaded,
    $storedName
);

$path = "cloud/{$user->id}/{$storedName}";
// after storing the file, we create a record in the database to keep track of it
        CloudFile::create([
            'user_id' => $user->id,
            'folder_id' => $folderId,
            'original_name' => $uploaded->getClientOriginalName(),
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => $uploaded->getMimeType(),
            'size' => $uploaded->getSize(),
            'version' => 1,
        ]);

        return back();
    }

    public function download(CloudFile $file)
    {
        abort_unless($file->user_id === auth()->id(), 403);

        return Storage::disk('local')->download(
            $file->path,
            $file->original_name
        );
    }

public function preview(CloudFile $file)
{
    abort_unless($file->user_id === auth()->id(), 403);

    if (!Storage::disk('local')->exists($file->path)) {
        abort(404);
    }

    return response()->file(
        Storage::disk('local')->path($file->path),
        [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
        ]
    );
}

    public function replace(Request $request, CloudFile $file)
    {
        abort_unless($file->user_id === auth()->id(), 403);

        $request->validate([
            'file' => ['required', 'file', 'max:2097152'],
        ]);

        FileVersion::create([
            'cloud_file_id' => $file->id,
            'version' => $file->version,
            'path' => $file->path,
            'stored_name' => $file->stored_name,
            'size' => $file->size,
            'mime_type' => $file->mime_type,
        ]);

        $uploaded = $request->file('file');

        $newVersion = $file->version + 1;
        $storedName = Str::uuid() . '.' . $uploaded->getClientOriginalExtension();

    Storage::disk('local')->putFileAs(
        "cloud/{$file->user_id}",
        $uploaded,
        $storedName
        );

$path = "cloud/{$file->user_id}/{$storedName}";

        $file->update([
            'original_name' => $uploaded->getClientOriginalName(),
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => $uploaded->getMimeType(),
            'size' => $uploaded->getSize(),
            'version' => $newVersion,
        ]);

        return back();
    }

    public function versions(CloudFile $file)
    {
        abort_unless($file->user_id === auth()->id(), 403);

        $versions = $file->versions()->latest()->get();

        return view('cloud.versions', compact('file', 'versions'));
    }

    public function destroy(CloudFile $file)
    {
        abort_unless($file->user_id === auth()->id(), 403);

        $file->delete();

        return back();
    }
}