<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DersController;
use Illuminate\Support\Facades\Route;

// Ana sayfa
Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Paneli
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Kullanıcı Paneli
Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');

    // Dersler
    Route::get('/dersler', [DersController::class, 'index'])->name('user.dersler');
    Route::get('/dersler/{id}', [DersController::class, 'dersDetay'])->name('user.ders.detay');
    Route::get('/dersler/{ders_id}/form/{form_id}', [DersController::class, 'formGoster'])->name('user.form.goster');
    Route::post('/dersler/notlari/kaydet', [DersController::class, 'notlariKaydet'])->name('ders.notlari.kaydet');
    Route::post('/dersler/katki/kaydet', [DersController::class, 'katkilariniKaydet'])->name('ders.katki.kaydet');
});