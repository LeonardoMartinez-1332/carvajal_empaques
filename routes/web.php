<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatosDBController;
use App\Http\Controllers\BitacoraCamionController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ProductImportController;


Route::get('/importar-datos', [DatosDBController::class, 'recuperarBD']);
Route::get('/mostrar-datos/{tabla}', [DatosDBController::class, 'mostrarDatos']);

Route::get('/crear-usuario', function () {
    return view('crear_usuario');
})->name('usuarios.form');

Route::post('/guardar-usuario', [DatosDBController::class, 'guardarUsuario'])->name('usuarios.guardar');

// Login / Logout
Route::get('/login', [DatosDBController::class, 'loginForm'])->name('login');
Route::post('/login', [DatosDBController::class, 'procesarLogin'])->name('login.procesar');
Route::post('/logout', [DatosDBController::class, 'logout'])->name('logout');



//USUARIO NORMAL
Route::middleware(['auth', 'role:usuario'])->group(function () {
    Route::get('/Consulta_producto', [DatosDBController::class, 'vistaConsultaProducto'])->name('usuario.panel');
    

});

//SUPERVISOR

Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('/panel-supervisor', [DatosDBController::class, 'vistaSupervisor'])->name('supervisor.panel');
    
    // Vista para consultar productos
    Route::get('/consulta-producto', [DatosDBController::class, 'vistaConsultaProducto'])->name('consulta.producto');

    // Bitácora para supervisor 
    Route::get('/bitacora-camiones', [BitacoraCamionController::class, 'vistaFormulario'])->name('bitacora.panel');

    // Nueva ruta para guardar el registro de camión
    Route::post('/bitacora-camiones/guardar', [BitacoraCamionController::class, 'guardarRegistro'])->name('bitacora.guardar');
});


//SUPERUSUARIO
Route::middleware(['auth', 'role:superusuario'])->group(function () {
    Route::get('/panel-superusuario', [DatosDBController::class, 'vistaSuperusuario'])->name('superusuario.panel');

    // Usuarios
    Route::get('/admin', [DatosDBController::class, 'verPanelAdmin'])->name('admin.panel');
    Route::delete('/usuarios/{id}', [DatosDBController::class, 'eliminarUsuario'])->name('usuarios.eliminar');
    Route::get('/usuarios/{id}/editar', [DatosDBController::class, 'editarUsuario'])->name('usuarios.editar');
    Route::put('/usuarios/{id}', [DatosDBController::class, 'actualizarUsuario'])->name('usuarios.actualizar');
    Route::post('/usuarios/{id}/desbloquear', [DatosDBController::class, 'desbloquear'])->name('usuarios.desbloquear');
    Route::post('/usuarios/{id}/resetear', [DatosDBController::class, 'resetearContrasena'])->name('usuarios.resetear');


    // Productos
    Route::get('/gestionar-productos', [DatosDBController::class, 'verPanelProductos'])->name('productos.panel');
    Route::get('/crear-producto', [DatosDBController::class, 'mostrarFormulario'])->name('productos.formulario');
    Route::post('/crear-producto', [DatosDBController::class, 'crearProducto'])->name('productos.crear');
    Route::delete('/productos/{id}', [DatosDBController::class, 'eliminarProducto'])->name('productos.eliminar');
    Route::get('/crear-producto', [DatosDBController::class, 'vistaCrearProducto'])->name('productos.crear');
    Route::get('/productos/{id}/editar', [DatosDBController::class, 'editarProducto'])->name('productos.editar');
    Route::put('/productos/{id}', [DatosDBController::class, 'actualizarProducto'])->name('productos.actualizar');


    // Bitácora
    Route::get('/admin-bitacora', [DatosDBController::class, 'verPanelBitacora'])->name('bitacora.admin');
    Route::get('/importar-bitacora', [DatosDBController::class, 'importarDesdeJson']);
    Route::delete('/bitacora/{id}', [DatosDBController::class, 'eliminarBitacora'])->name('bitacora.eliminar');
    Route::get('/bitacora/{id}/editar', [DatosDBController::class, 'editarBitacora'])->name('bitacora.editar');
    Route::put('/bitacora/{id}', [DatosDBController::class, 'actualizarBitacora'])->name('bitacora.actualizar');
    Route::get('/admin-bitacora', [DatosDBController::class, 'verPanelBitacora'])->name('bitacora.admin');
    Route::put('/bitacora/{id}/actualizar', [DatosDBController::class, 'actualizar'])->name('bitacora.actualizar');

    // graficas del superusuario

    Route::get('/superusuario', [PanelController::class, 'vistaSuperusuario'])->name('superusuario.panel');

});
