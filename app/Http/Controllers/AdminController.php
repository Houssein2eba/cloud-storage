<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CloudFile;
use App\Models\CloudFolder;

class AdminController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->is_admin, 403);

        return view('dashboard', [
            'usersCount' => User::count(),
            'filesCount' => CloudFile::count(),
            'foldersCount' => CloudFolder::count(),
            'storageUsed' => CloudFile::sum('size'),
            'users' => User::latest()->get(),
        ]);
    }
}