<?php

use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas públicas (sin autenticación)
Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');
Route::get('/solicitudes/crear', [SolicitudController::class, 'create'])->name('solicitudes.create');
Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');
Route::get('/solicitudes/{solicitud}', [SolicitudController::class, 'show'])->name('solicitudes.show');

// Opcionales de administración: hacer públicas también
Route::prefix('admin')->group(function () {
    Route::get('/solicitudes', [SolicitudController::class, 'adminIndex'])->name('admin.solicitudes.index');
    Route::patch('/solicitudes/{solicitud}/estado', [SolicitudController::class, 'updateEstado'])->name('admin.solicitudes.updateEstado');
});
