<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CloudController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\TrashController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





Route::get('/', function () {
    return redirect()->route('cloud.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/cloud', [CloudController::class, 'index'])->name('cloud.index');

    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');

    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::get('/files/{file}/preview', [FileController::class, 'preview'])->name('files.preview');
    Route::post('/files/{file}/replace', [FileController::class, 'replace'])->name('files.replace');
    Route::get('/files/{file}/versions', [FileController::class, 'versions'])->name('files.versions');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');

    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/files/{id}/restore', [TrashController::class, 'restoreFile'])->name('trash.files.restore');
    Route::delete('/trash/files/{id}/delete', [TrashController::class, 'forceDeleteFile'])->name('trash.files.force-delete');
    Route::post('/trash/folders/{id}/restore', [TrashController::class, 'restoreFolder'])->name('trash.folders.restore');

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
});



require __DIR__.'/auth.php';
