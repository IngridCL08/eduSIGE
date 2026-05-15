<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CarreraController;
use App\Http\Controllers\Admin\PeriodoController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\BitacoraController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Usuarios del sistema
Route::resource('usuarios', UserController::class);
Route::patch('usuarios/{usuario}/toggle', [UserController::class, 'toggleActivo'])->name('usuarios.toggle');

// Roles y permisos
Route::resource('roles', RoleController::class)->except(['show']);

// Catálogos
Route::resource('carreras', CarreraController::class)->except(['show']);
Route::patch('carreras/{carrera}/toggle', [CarreraController::class, 'toggle'])->name('carreras.toggle');

Route::resource('periodos', PeriodoController::class)->except(['show']);
Route::patch('periodos/{periodo}/activar', [PeriodoController::class, 'activar'])->name('periodos.activar');

// Configuración del sistema
Route::get('/configuracion', [ConfigController::class, 'index'])->name('config.index');
Route::put('/configuracion', [ConfigController::class, 'update'])->name('config.update');

// Bitácora
Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora');
