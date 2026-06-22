<?php

use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProductoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::apiResource('productos', ProductoController::class)->names('api.productos');
    Route::apiResource('categorias', CategoriaController::class)->names('api.categorias');
});
