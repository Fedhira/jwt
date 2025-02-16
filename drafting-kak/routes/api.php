<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\UserController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth')->name('api.logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.auth')->name('api.refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('jwt.auth')->name('api.me');
});

// Admin routes (full access)
Route::group([
    'middleware' => ['jwt.auth', 'role:admin'],
    'prefix' => 'admin'
], function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('api.admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('api.admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('api.admin.users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('api.admin.users.show');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('api.admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('api.admin.users.destroy');
});

// Staff routes (User limited access)
Route::group([
    'middleware' => ['jwt.auth', 'role:staff'],
    'prefix' => 'staff'
], function () {
    Route::get('/users/{id}', [UserController::class, 'show'])->name('api.users.show');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('api.users.update');
});