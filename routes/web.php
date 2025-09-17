<?php

use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\Auth\AuthController;
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

Route::get('/', function () {
    return redirect()->route('solicitudes.index');
});

Route::middleware(['auth'])->group(function () {
    // Rutas para ciudadanos
    Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('/solicitudes/crear', [SolicitudController::class, 'create'])->name('solicitudes.create')->middleware('role:ciudadano');
    Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store')->middleware('role:ciudadano');
    Route::get('/solicitudes/{solicitud}', [SolicitudController::class, 'show'])->name('solicitudes.show');

    // Rutas para funcionarios
    Route::get('/admin/solicitudes', [SolicitudController::class, 'adminIndex'])->name('admin.solicitudes.index')->middleware('role:funcionario');
    Route::patch('/admin/solicitudes/{solicitud}/estado', [SolicitudController::class, 'updateEstado'])->name('admin.solicitudes.updateEstado')->middleware('role:funcionario');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
