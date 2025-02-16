<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\AuthController;

// Halaman login sebagai halaman awal
Route::get('/', [AuthController::class, 'showLoginForm'])->name('/');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/web/login', [AuthController::class, 'webLogin'])->name('web.login');
Route::get('/web/logout', [AuthController::class, 'webLogout'])->name('web.logout');

// Proteksi halaman admin, user, dan supervisor dengan session-based auth
Route::middleware(['auth.session'])->group(function () {

    // ✅ Rute untuk pengguna biasa (User)
    Route::prefix('user')->middleware('user.auth')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('user.index');
        Route::get('/daftar', [UserController::class, 'daftar'])->name('user.daftar');
        Route::get('/draft', [UserController::class, 'draft'])->name('user.draft');
        Route::get('/add_draft', [UserController::class, 'add_draft'])->name('user.add_draft');
        Route::get('/edit_draft/{id}', [UserController::class, 'edit_draft'])->name('user.edit_draft');
        Route::get('/upload_draft/{id}', [UserController::class, 'upload_draft'])->name('user.upload_draft');
        Route::get('/laporan', [UserController::class, 'laporan'])->name('user.laporan');
        Route::get('/faq', [UserController::class, 'faq'])->name('user.faq');

        // Rute CRUD Draft
        Route::post('/draft/store', [UserController::class, 'store'])->name('user.draft.store');
        Route::post('/draft/update/{id}', [UserController::class, 'update'])->name('user.draft.update');
        Route::delete('/draft/{id}', [UserController::class, 'destroy'])->name('user.draft.destroy');

        // Rute Upload File
        Route::post('/draft/upload', [UserController::class, 'upload'])->name('user.draft.upload');

        // Rute Revisi KAK
        Route::post('/kak/reject', [UserController::class, 'rejectKak'])->name('user.kak.reject');

        // Rute Download & Preview
        Route::get('/download-word/{id}', [UserController::class, 'downloadWord'])->name('user.downloadWord');
        Route::get('/download-pdf/{id}', [UserController::class, 'downloadPdf'])->name('user.downloadPdf');
        Route::get('/preview-pdf/{id}', [UserController::class, 'previewPdf'])->name('user.previewPdf');
    });

    // ✅ Rute untuk Admin
    Route::prefix('admin')->middleware('admin.auth')->group(function () {
        Route::get('/index', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/kategori', [AdminController::class, 'kategori'])->name('admin.kategori');
        Route::get('/daftar', [AdminController::class, 'daftar'])->name('admin.daftar');
        Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
        Route::get('/faq', [AdminController::class, 'faq'])->name('admin.faq');

        // CRUD Pengguna
        Route::post('/users/store', [AdminController::class, 'store'])->name('admin.users.store');
        Route::post('/users/update/{id}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

        // CRUD Kategori
        Route::post('/kategori/store', [AdminController::class, 'storeKategori'])->name('admin.kategori.store');
        Route::post('/kategori/update/{id}', [AdminController::class, 'updateKategori'])->name('admin.kategori.update');
        Route::delete('/kategori/{id}', [AdminController::class, 'destroyKategori'])->name('admin.kategori.destroy');

        // Rute Download & Preview
        Route::get('/download-word/{id}', [AdminController::class, 'downloadWord'])->name('admin.downloadWord');
        Route::get('/download-pdf/{id}', [AdminController::class, 'downloadPdf'])->name('admin.downloadPdf');
        Route::get('/preview-pdf/{id}', [AdminController::class, 'previewPdf'])->name('admin.previewPdf');
    });

    // ✅ Rute untuk Supervisor
    Route::prefix('supervisor')->middleware('supervisor.auth')->group(function () {
        Route::get('/index', [SupervisorController::class, 'index'])->name('supervisor.index');
        Route::get('/daftar', [SupervisorController::class, 'daftar'])->name('supervisor.daftar');
        Route::get('/laporan', [SupervisorController::class, 'laporan'])->name('supervisor.laporan');
        Route::get('/faq', [SupervisorController::class, 'faq'])->name('supervisor.faq');

        // Menolak & Menyetujui KAK
        Route::post('/kak/reject', [SupervisorController::class, 'rejectKak'])->name('supervisor.kak.reject');
        Route::post('/kak/disetujui/{id}', [SupervisorController::class, 'approve'])->name('supervisor.approve');

        // Rute Download & Preview
        Route::get('/download-word/{id}', [SupervisorController::class, 'downloadWord'])->name('supervisor.downloadWord');
        Route::get('/download-pdf/{id}', [SupervisorController::class, 'downloadPdf'])->name('supervisor.downloadPdf');
        Route::get('/preview-pdf/{id}', [SupervisorController::class, 'previewPdf'])->name('supervisor.previewPdf');
    });
});
