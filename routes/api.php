<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\BitacoraCamionController;
use App\Http\Controllers\Api\TiDirectaController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\Api\AdminUsuarioController;
use App\Http\Controllers\Api\UserUnlockRequestController;


// Rutas públicas (login)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/unlock-request', [AuthController::class, 'unlockRequest']);

// Rutas protegidas (requieren token Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Perfil / logout 
    Route::get('/me',    [AuthController::class, 'me']);
    Route::post('/logout',[AuthController::class, 'logout']);

    // Importar productos desde Excel/CSV
    // Método: POST /api/products/import  (multipart/form-data con campo "file")
    Route::post('/products/import', [ProductImportController::class, 'import']);

    //supervisor
    Route::get('/productos', [ProductoController::class, 'index']);
    Route::get('/bitacora-camiones',          [BitacoraCamionController::class, 'index']);
    Route::post('/bitacora-camiones',         [BitacoraCamionController::class, 'store']);
    Route::get('/bitacora-camiones/pendientes',      [BitacoraCamionController::class, 'pendientes']);
    Route::get('/bitacora-camiones/mis-solicitudes', [BitacoraCamionController::class, 'misSolicitudes']);
    Route::get('/bitacora-camiones/{id}',     [BitacoraCamionController::class, 'show'])
        ->whereNumber('id');
    Route::put('/bitacora-camiones/{id}',     [BitacoraCamionController::class, 'update'])
        ->whereNumber('id');
    Route::delete('/bitacora-camiones/{id}',  [BitacoraCamionController::class, 'destroy'])
        ->whereNumber('id');

    // admin 
    Route::post('/bitacora-camiones/{id}/aprobar', [BitacoraCamionController::class, 'aprobar'])
        ->whereNumber('id');
    Route::post('/bitacora-camiones/{id}/rechazar', [BitacoraCamionController::class, 'rechazar'])
        ->whereNumber('id');
    Route::get('/admin/usuarios', [AdminUsuarioController::class, 'index']);
    Route::post('/admin/usuarios', [AdminUsuarioController::class, 'store']);
    Route::put('/admin/usuarios/{id}', [AdminUsuarioController::class, 'update']);
    Route::patch('/admin/usuarios/{id}/estado', [AdminUsuarioController::class, 'updateEstado']);
    Route::delete('/admin/usuarios/{id}', [AdminUsuarioController::class, 'destroy']);
    // --- Desbloqueo de usuarios (ADMIN) ---
    Route::get('/admin/unlock-requests', [UserUnlockRequestController::class, 'index']);
    Route::post('/admin/unlock-requests/{id}/approve', [UserUnlockRequestController::class, 'approve'])
        ->whereNumber('id');
    Route::post('/admin/unlock-requests/{id}/reject', [UserUnlockRequestController::class, 'reject'])
        ->whereNumber('id');
    



    // Rutas para rol jobs (analistas de facturación)
    Route::get('/jobs/ti', [TiDirectaController::class, 'index']);
    Route::get('/jobs/ti/{id}', [TiDirectaController::class, 'show']);
    Route::post('/jobs/ti', [TiDirectaController::class, 'store']);
    Route::post('/jobs/ti/{id}/cancelar', [TiDirectaController::class, 'cancelar']);   
    Route::get('/jobs/productos', [JobsController::class, 'productosConStock']);
    Route::get('/jobs/productos/{almacenId}', [JobsController::class, 'productosConStock'])
    ->whereNumber('almacenId');
    Route::post('/jobs/ti', [JobsController::class, 'crearTi']); 
    Route::get('/jobs/ti',         [JobsController::class, 'listarTi']); // historial
     Route::get('/jobs/ti/{id}', [JobsController::class, 'verTi']);    // detalle
    Route::get('/jobs/ti/{id}/pdf', [JobsController::class, 'pdfTi']);


});
