<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\FolderController; // Import FolderController
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AssetController::class, 'index'])->name('dashboard');

    // Rute untuk aset
    Route::get('/assets', [AssetController::class, 'getAssets'])->name('assets.list');
    Route::get('/assets/folder/{folderId}', [AssetController::class, 'getAssetsByFolder'])->name('assets.folder');
    Route::get('/assets/page/{page}', [AssetController::class, 'getAssets'])->name('assets.page');
    Route::get('/assets/folder/{folderId}/page/{page}', [AssetController::class, 'getAssetsByFolder'])->name('assets.folder.page');
    Route::post('/upload', [AssetController::class, 'upload'])->name('asset.upload');
    Route::get('/assets/{id}', [AssetController::class, 'show'])->name('assets.show');
    Route::put('/assets/{id}', [AssetController::class, 'update'])->name('assets.update');
    Route::delete('/assets/{id}', [AssetController::class, 'destroy'])->name('assets.destroy');
    Route::post('/assets/{id}/like', [AssetController::class, 'like'])->name('assets.like');
    Route::delete('/assets/{id}/like', [AssetController::class, 'unlike'])->name('assets.unlike');

    // Rute untuk komentar
    Route::get('/assets/{id}/comments', [AssetController::class, 'getComments'])->name('assets.comments');
    Route::post('/assets/{id}/comments', [AssetController::class, 'storeComment'])->name('assets.comments.store');

    // Rute untuk folder
    Route::post('/folders', [FolderController::class, 'create'])->name('folder.create');
    Route::get('/folders', [FolderController::class, 'list'])->name('folders.list');
    Route::put('/folders/{id}', [FolderController::class, 'update'])->name('folder.update');
    Route::delete('/folders/{id}', [FolderController::class, 'destroy'])->name('folder.destroy');

    // Rute untuk profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});
