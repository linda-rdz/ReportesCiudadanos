<?php

use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\HomeController;
// Eliminado: rutas/auth y controladores admin/ciudadano
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ruta pública de inicio
Route::get('/', [HomeController::class, 'index'])->name('home');

// Panel de funcionarios (sin login, ruta separada)
Route::prefix('funcionarios')->group(function () {
    Route::get('/solicitudes', [SolicitudController::class, 'funcionariosIndex'])->name('funcionarios.solicitudes.index');
    Route::patch('/solicitudes/{solicitud}/estado', [SolicitudController::class, 'funcionariosUpdateEstado'])->name('funcionarios.solicitudes.updateEstado');
});

// Rutas públicas para ciudadanos (sin login requerido)
Route::prefix('solicitudes')->group(function () {
    Route::get('/', [SolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('/crear', [SolicitudController::class, 'create'])->name('solicitudes.create');
    Route::post('/', [SolicitudController::class, 'store'])->name('solicitudes.store');
    Route::get('/buscar', [SolicitudController::class, 'buscar'])->name('solicitudes.buscar');
    Route::get('/{solicitud}', [SolicitudController::class, 'show'])->name('solicitudes.show');
});
