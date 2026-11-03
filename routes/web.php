<?php

use App\Http\Controllers\AdminBarberoController;
use App\Http\Controllers\AdminClienteController;
use App\Http\Controllers\AdminConfiguracionController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminReporteController;
use App\Http\Controllers\AdminReservaController;
use App\Http\Controllers\AdminServicioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PublicReservaController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::post('/reservas', [PublicReservaController::class, 'store'])->name('reservas.store');
Route::get('/consulta-cita', [PublicReservaController::class, 'consultar'])->name('reservas.consultar');
Route::post('/consulta-cita', [PublicReservaController::class, 'consultar']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/reservas', [AdminReservaController::class, 'index'])->name('admin.reservas');
    Route::patch('/admin/reservas/{reserva}/status', [AdminReservaController::class, 'updateStatus'])->name('admin.reservas.status');
    Route::get('/admin/clientes', [AdminClienteController::class, 'index'])->name('admin.clientes');
    Route::get('/admin/barberos', [AdminBarberoController::class, 'index'])->name('admin.barberos');
    Route::post('/admin/barberos', [AdminBarberoController::class, 'store'])->name('admin.barberos.store');
    Route::patch('/admin/barberos/{barbero}/estado', [AdminBarberoController::class, 'updateEstado'])->name('admin.barberos.estado');
    Route::delete('/admin/barberos/{barbero}', [AdminBarberoController::class, 'destroy'])->name('admin.barberos.destroy');
    Route::get('/admin/servicios', [AdminServicioController::class, 'index'])->name('admin.servicios');
    Route::post('/admin/servicios', [AdminServicioController::class, 'store'])->name('admin.servicios.store');
    Route::post('/admin/servicios/{servicio}', [AdminServicioController::class, 'update'])->name('admin.servicios.update');
    Route::patch('/admin/servicios/{servicio}/toggle', [AdminServicioController::class, 'toggleActivo'])->name('admin.servicios.toggle');
    Route::delete('/admin/servicios/{servicio}', [AdminServicioController::class, 'destroy'])->name('admin.servicios.destroy');
    Route::get('/admin/reportes', [AdminReporteController::class, 'index'])->name('admin.reportes');

    Route::get('/admin/perfil', [AdminProfileController::class, 'show'])->name('admin.profile');
    Route::patch('/admin/perfil/info', [AdminProfileController::class, 'updateInfo'])->name('admin.profile.info');
    Route::patch('/admin/perfil/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');

    Route::get('/admin/configuracion', [AdminConfiguracionController::class, 'index'])->name('admin.configuracion');
    Route::post('/admin/configuracion', [AdminConfiguracionController::class, 'update'])->name('admin.configuracion.update');
});
