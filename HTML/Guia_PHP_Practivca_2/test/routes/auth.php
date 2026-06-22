<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('login', [LoginController::class, 'showForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', LogoutController::class)->name('logout');
});
