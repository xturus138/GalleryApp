<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssetController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AssetController::class, 'index'])->name('dashboard');
    Route::get('/assets', [AssetController::class, 'getAssets'])->name('assets.list'); // Ini rute baru
    Route::post('/upload', [AssetController::class, 'upload'])->name('asset.upload');
});