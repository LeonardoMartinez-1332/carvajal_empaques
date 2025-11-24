<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\BitacoraCamionController;
use App\Http\Controllers\Api\TiDirectaController;
use App\Http\Controllers\JobsController;


// Rutas públicas (login)
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren token Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Perfil / logout 
    Route::get('/me',    [AuthController::class, 'me']);
    Route::post('/logout',[AuthController::class, 'logout']);

    // Importar productos desde Excel/CSV
    // Método: POST /api/products/import  (multipart/form-data con campo "file")
    Route::post('/products/import', [ProductImportController::class, 'import']);

    // listar productos 
    Route::get('/productos', [ProductoController::class, 'index']);


    // Bitácora de camiones
    Route::get('/bitacora-camiones',          [BitacoraCamionController::class, 'index']);
    Route::post('/bitacora-camiones',         [BitacoraCamionController::class, 'store']);

    // ESTAS DOS ANTES DEL {id}
    Route::get('/bitacora-camiones/pendientes',      [BitacoraCamionController::class, 'pendientes']);
    Route::get('/bitacora-camiones/mis-solicitudes', [BitacoraCamionController::class, 'misSolicitudes']);

    // LAS QUE USAN {id}
    Route::get('/bitacora-camiones/{id}',     [BitacoraCamionController::class, 'show'])
        ->whereNumber('id');
    Route::put('/bitacora-camiones/{id}',     [BitacoraCamionController::class, 'update'])
        ->whereNumber('id');
    Route::delete('/bitacora-camiones/{id}',  [BitacoraCamionController::class, 'destroy'])
        ->whereNumber('id');

    // admin aprobación
    Route::post('/bitacora-camiones/{id}/aprobar', [BitacoraCamionController::class, 'aprobar'])
        ->whereNumber('id');
    Route::post('/bitacora-camiones/{id}/rechazar', [BitacoraCamionController::class, 'rechazar'])
        ->whereNumber('id');

    // 🔐 Rutas para rol jobs (analistas de facturación)
    Route::get('/jobs/ti', [TiDirectaController::class, 'index']);
    Route::get('/jobs/ti/{id}', [TiDirectaController::class, 'show']);
    Route::post('/jobs/ti', [TiDirectaController::class, 'store']);
    Route::post('/jobs/ti/{id}/cancelar', [TiDirectaController::class, 'cancelar']);   
    Route::get('/jobs/productos/{almacenId}', [JobsController::class, 'productosConStock'])
    ->whereNumber('almacenId');
    Route::post('/jobs/ti', [JobsController::class, 'crearTi']); 
    Route::get('/jobs/ti',         [JobsController::class, 'listarTi']); // historial
    Route::get('/jobs/ti/{ti}',    [JobsController::class, 'verTi']);    // detalle
    Route::get('/jobs/ti/{id}/pdf', [JobsController::class, 'pdfTi']);


});
