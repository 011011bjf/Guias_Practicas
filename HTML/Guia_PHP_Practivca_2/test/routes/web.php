<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('productos', ProductoViewController::class);
});

require __DIR__.'/auth.php';
