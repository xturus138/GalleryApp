<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\FolderController; // Import FolderController

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
    Route::post('/upload', [AssetController::class, 'upload'])->name('asset.upload');
    Route::get('/assets/{id}', [AssetController::class, 'show'])->name('assets.show');
    Route::put('/assets/{id}', [AssetController::class, 'update'])->name('assets.update');
    Route::delete('/assets/{id}', [AssetController::class, 'destroy'])->name('assets.destroy');

    // Rute untuk folder
    Route::post('/folders', [FolderController::class, 'create'])->name('folder.create');
    Route::get('/folders', [FolderController::class, 'list'])->name('folders.list');
});