<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth')->name('api.logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.auth')->name('api.refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('jwt.auth')->name('api.me');
});
