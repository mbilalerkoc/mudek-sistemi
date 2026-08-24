<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DersController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;

// Ana sayfa
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/password/direct-reset', [AuthController::class, 'directReset'])->name('password.direct-reset');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// SÜPER ADMİN PANELİ
// ==========================================
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/login-history', [AdminController::class, 'loginHistory'])->name('login.history');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kullanıcı Yönetimi
    Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
    Route::get('/users/ekle', [AdminController::class, 'userCreate'])->name('users.ekle');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

    // Ders Yönetimi
    Route::get('/courses', [AdminController::class, 'courseIndex'])->name('courses.index');
    Route::post('/courses', [AdminController::class, 'courseStore'])->name('courses.store');
    Route::post('/courses/assign-teacher', [AdminController::class, 'assignTeacher'])->name('courses.assign');
    Route::post('/courses/remove-teacher', [AdminController::class, 'removeTeacher'])->name('courses.remove');
    Route::get('/courses/{id}/edit', [AdminController::class, 'courseEdit'])->name('courses.edit');
    Route::put('/courses/{id}', [AdminController::class, 'courseUpdate'])->name('courses.update');
    Route::delete('/courses/{id}', [AdminController::class, 'courseDestroy'])->name('courses.destroy');

    // Ders - Öğrenci İlişki Yönetimi
    Route::get('/courses/{id}/ogrenciler', [AdminController::class, 'dersOgrencileri'])->name('courses.ogrenciler');
    Route::post('/courses/{id}/ogrenci-ekle', [AdminController::class, 'dersOgrenciEkle'])->name('courses.ogrenci.ekle');
    Route::delete('/courses/{id}/ogrenci-cikar/{student_id}', [AdminController::class, 'dersOgrenciCikar'])->name('courses.ogrenci.cikar');
    Route::delete('/courses/{id}/ogrenci-cikar-toplu', [AdminController::class, 'dersOgrenciCikarToplu'])->name('courses.ogrenci.cikar.toplu');

    // Öğrenci Yönetimi
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students/import', [StudentController::class, 'importExcel'])->name('students.import');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Dersler
    Route::get('/dersler', [DersController::class, 'adminIndex'])->name('dersler');
    Route::get('/dersler/{id}', [DersController::class, 'dersDetay'])->name('ders.detay');
    Route::get('/dersler/{ders_id}/form/{form_id}', [DersController::class, 'formGoster'])->name('form.goster');
    Route::get('/dersler/{id}/notlari/duzenle', [DersController::class, 'notlariDuzenle'])->name('ders.notlari.duzenle');
    Route::post('/dersler/notlari/kaydet', [DersController::class, 'notlariKaydet'])->name('ders.notlari.kaydet');

    // Ödevler
    Route::post('/dersler/{course_id}/odevler', [AssignmentController::class, 'store'])->name('dersler.odevler.store');
    Route::delete('/odevler/{id}', [AssignmentController::class, 'destroy'])->name('dersler.odevler.destroy');
    Route::get('/dersler/{ders_id}/odevler/{odev_id}/teslimler', [AssignmentController::class, 'teslimler'])->name('dersler.odevler.teslimler');
    Route::post('/dersler/{ders_id}/odevler/{odev_id}/teslimler', [AssignmentController::class, 'teslimKaydet'])->name('dersler.odevler.teslimler.kaydet');

    // Sınavlar
    Route::put('/sinavlar/{exam_id}', [ExamController::class, 'updateExam'])->name('sinavlar.guncelle');
    Route::get('/sinavlar/{exam_id}/detay', [ExamController::class, 'studentExamDetails'])->name('sinavlar.detay');
    Route::post('/sinavlar/{exam_id}/soru', [ExamController::class, 'storeQuestion'])->name('sinavlar.soru.kaydet');
    Route::delete('/sinavlar/soru/{id}', [ExamController::class, 'deleteQuestion'])->name('sinavlar.soru.sil');
    Route::get('/sinavlar/{exam_id}/cevaplar', [ExamController::class, 'cevaplarGoster'])->name('sinavlar.cevaplar');
    Route::get('/sinavlar/{exam_id}/cevaplar/duzenle', [ExamController::class, 'cevaplarEdit'])->name('sinavlar.cevaplar.duzenle');
    Route::post('/sinavlar/{exam_id}/cevaplar/kaydet', [ExamController::class, 'cevaplariKaydet'])->name('sinavlar.cevaplar.kaydet');
    Route::post('/sinavlar/{exam_id}/cevaplar/import', [ExamController::class, 'importExcel'])->name('sinavlar.cevaplar.import');
    Route::post('/sinavlar/{exam_id}/notlar', [ExamController::class, 'saveStudentGrades'])->name('sinavlar.ogrenci.notlari.kaydet');
    Route::post('/sinavlar/{exam_id}/ornek-kagitlar', [ExamController::class, 'saveSampleExamPapers'])->name('sinavlar.ornek.kaydet');
});

// ==========================================
// KULLANICI PANELİ
// ==========================================
Route::prefix('user')->middleware(['auth'])->name('user.')->group(function () {

    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'profileUpdate'])->name('profile.update');

    // Dersler
    Route::get('/dersler', [DersController::class, 'index'])->name('dersler');
    Route::get('/dersler/{id}', [DersController::class, 'dersDetay'])->name('ders.detay');
    Route::get('/dersler/{ders_id}/form/{form_id}', [DersController::class, 'formGoster'])->name('form.goster');

    // Ödevler
    Route::post('/dersler/{course_id}/odevler', [AssignmentController::class, 'store'])->name('dersler.odevler.store');
    Route::delete('/odevler/{id}', [AssignmentController::class, 'destroy'])->name('dersler.odevler.destroy');
    Route::get('/dersler/{ders_id}/odevler/{odev_id}/teslimler', [AssignmentController::class, 'teslimler'])->name('dersler.odevler.teslimler');
    Route::post('/dersler/{ders_id}/odevler/{odev_id}/teslimler', [AssignmentController::class, 'teslimKaydet'])->name('dersler.odevler.teslimler.kaydet');

    // Sınavlar
    Route::put('/sinavlar/{exam_id}', [ExamController::class, 'updateExam'])->name('sinavlar.guncelle');
    Route::get('/dersler/{ders_id}/sinavlar', [ExamController::class, 'index'])->name('dersler.sinavlar');
    Route::get('/sinavlar/{exam_id}/detay', [ExamController::class, 'studentExamDetails'])->name('sinavlar.detay');
    Route::post('/sinavlar/{exam_id}/soru', [ExamController::class, 'storeQuestion'])->name('sinavlar.soru.kaydet');
    Route::delete('/sinavlar/soru/{id}', [ExamController::class, 'deleteQuestion'])->name('sinavlar.soru.sil');
    Route::get('/sinavlar/{exam_id}/cevaplar', [ExamController::class, 'cevaplarGoster'])->name('sinavlar.cevaplar');
    Route::get('/sinavlar/{exam_id}/cevaplar/duzenle', [ExamController::class, 'cevaplarEdit'])->name('sinavlar.cevaplar.duzenle');
    Route::post('/sinavlar/{exam_id}/cevaplar/kaydet', [ExamController::class, 'cevaplariKaydet'])->name('sinavlar.cevaplar.kaydet');
    Route::post('/sinavlar/{exam_id}/cevaplar/import', [ExamController::class, 'importExcel'])->name('sinavlar.cevaplar.import');
    Route::post('/sinavlar/{exam_id}/notlar', [ExamController::class, 'saveStudentGrades'])->name('sinavlar.ogrenci.notlari.kaydet');
    Route::post('/sinavlar/{exam_id}/ornek-kagitlar', [ExamController::class, 'saveSampleExamPapers'])->name('sinavlar.ornek.kaydet');
});