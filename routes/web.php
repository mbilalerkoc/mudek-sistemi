<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DersController;

// Ana sayfa → direkt login'e yönlendir
Route::get('/', function () {
    return view('welcome');
});

// Login sayfası
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
    Route::get('/dersler', [UserController::class, 'dersler'])->name('user.dersler');
});


Route::get('/user/dersler', [DersController::class, 'index'])->name('user.dersler');

Route::get('/user/dersler/{id}', [DersController::class, 'dersDetay'])->name('user.ders.detay');

Route::get('/user/dersler/{ders_id}/form/{form_id}', [DersController::class, 'formGoster'])->name('user.form.goster');

Route::post('/user/dersler/kaydet', [App\Http\Controllers\DersController::class, 'katkilariniKaydet'])->name('ders.katki.kaydet');