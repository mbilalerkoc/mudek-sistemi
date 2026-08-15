<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

// Ana sayfa
Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//Süper Admin Paneli
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Öğretmenleri Listeleme
    Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
    Route::get('/users/ekle', [AdminController::class, 'userCreate'])->name('users.ekle');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

    // Ders Yönetimi ve Atama
    Route::get('/courses', [AdminController::class, 'courseIndex'])->name('courses.index');
    Route::post('/courses', [AdminController::class, 'courseStore'])->name('courses.store');
    Route::post('/courses/assign-teacher', [AdminController::class, 'assignTeacher'])->name('courses.assign');
    Route::get('/courses/{id}/edit', [AdminController::class, 'courseEdit'])->name('courses.edit');
    Route::put('/courses/{id}', [AdminController::class, 'courseUpdate'])->name('courses.update');
    Route::delete('/courses/{id}', [AdminController::class, 'courseDestroy'])->name('courses.destroy');

    // Öğrenci Yönetimi
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Ders-Öğrenci İlişki Yönetimi
    Route::get('/courses/{id}/ogrenciler', [AdminController::class, 'dersOgrencileri'])->name('courses.ogrenciler');
    Route::post('/courses/{id}/ogrenci-ekle', [AdminController::class, 'dersOgrenciEkle'])->name('courses.ogrenci.ekle');
    Route::delete('/courses/{id}/ogrenci-cikar/{student_id}', [AdminController::class, 'dersOgrenciCikar'])->name('courses.ogrenci.cikar');
    Route::delete('/courses/{id}/ogrenci-cikar-toplu', [AdminController::class, 'dersOgrenciCikarToplu'])->name('courses.ogrenci.cikar.toplu');
});

// Kullanıcı Paneli
Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');

    // Dersler
    Route::get('/dersler', [DersController::class, 'index'])->name('user.dersler');
    Route::get('/dersler/{id}', [DersController::class, 'dersDetay'])->name('user.ders.detay');
    Route::get('/dersler/{ders_id}/form/{form_id}', [DersController::class, 'formGoster'])->name('user.form.goster');
    Route::get('/dersler/{id}/notlari/duzenle', [DersController::class, 'notlariDuzenle'])->name('user.ders.notlari.duzenle');
    Route::post('/dersler/notlari/kaydet', [DersController::class, 'notlariKaydet'])->name('ders.notlari.kaydet');
    Route::post('/dersler/katki/kaydet', [DersController::class, 'katkilariniKaydet'])->name('ders.katki.kaydet');
});