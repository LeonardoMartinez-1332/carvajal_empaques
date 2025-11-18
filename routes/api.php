<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\Api\ProductoController;

// Rutas públicas (login)
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren token Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Perfil / logout 
    Route::get('/me',    [AuthController::class, 'me']);
    Route::post('/logout',[AuthController::class, 'logout']);

    // 👉 Importar productos desde Excel/CSV
    // Método: POST /api/products/import  (multipart/form-data con campo "file")
    Route::post('/products/import', [ProductImportController::class, 'import']);

    // listar productos 
    Route::get('/productos', [ProductoController::class, 'index']);
    // Otras rutas API protegidas pueden ir aquí
});
