<?php

use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminSolicitudController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin\AdminEmpleadoController;
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

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/auth/check-email', [LoginController::class, 'checkEmail'])->name('auth.check_email');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Recuperación de contraseña
Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

// Rutas públicas para ciudadanos (sin login requerido)
Route::prefix('solicitudes')->group(function () {
    Route::get('/', [SolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('/crear', [SolicitudController::class, 'create'])->name('solicitudes.create');
    Route::post('/', [SolicitudController::class, 'store'])->name('solicitudes.store');
    Route::get('/buscar', [SolicitudController::class, 'buscar'])->name('solicitudes.buscar');
    Route::get('/{solicitud}', [SolicitudController::class, 'show'])->name('solicitudes.show');
});

Route::prefix('admin/solicitudes')->group(function () {
    Route::get('/', [AdminSolicitudController::class, 'index'])->name('admin.solicitudes.index');
    Route::get('/{solicitud}', [AdminSolicitudController::class, 'show'])->name('admin.solicitudes.show');
    Route::patch('/{solicitud}/estado', [AdminSolicitudController::class, 'updateEstado'])->name('admin.solicitudes.updateEstado');
    Route::post('/{solicitud}/mensajes', [AdminSolicitudController::class, 'storeMensaje'])->name('admin.solicitudes.mensajes.store');
    Route::delete('/{solicitud}', [AdminSolicitudController::class, 'destroy'])->name('admin.solicitudes.destroy');
});

Route::prefix('admin/empleados')->group(function () {
    Route::get('/', [AdminEmpleadoController::class, 'index'])->name('admin.empleados.index');
    Route::get('/crear', [AdminEmpleadoController::class, 'create'])->name('admin.empleados.create');
    Route::post('/', [AdminEmpleadoController::class, 'store'])->name('admin.empleados.store');
    Route::get('/{empleado}/editar', [AdminEmpleadoController::class, 'edit'])->name('admin.empleados.edit');
    Route::patch('/{empleado}', [AdminEmpleadoController::class, 'update'])->name('admin.empleados.update');
    Route::delete('/{empleado}', [AdminEmpleadoController::class, 'destroy'])->name('admin.empleados.destroy');
});
