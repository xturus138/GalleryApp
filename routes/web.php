<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('auth.login'); // Ini benar, karena file 'login.blade.php' ada di dalam folder 'auth'
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');