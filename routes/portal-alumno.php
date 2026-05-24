<?php

use App\Http\Controllers\Portal\Alumno\AuthController;
use App\Http\Controllers\Portal\Alumno\DashboardController;
use App\Http\Controllers\Portal\Alumno\HistorialController;
use App\Http\Controllers\Portal\Alumno\AdeudoController;
use App\Http\Controllers\Portal\Alumno\PerfilController;
use App\Http\Controllers\Portal\Alumno\DocumentoAlumnoController;
use App\Http\Controllers\Portal\Alumno\PasswordController;
use App\Http\Controllers\Portal\Alumno\PagosController;
use Illuminate\Support\Facades\Route;

// ─── Login / Logout (sin auth) ────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ─── Rutas protegidas ─────────────────────────────────────────
Route::middleware('auth.alumno')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Cambio de contraseña (accesible aunque must_change_password sea true)
    Route::get('/cambiar-password',  [PasswordController::class, 'edit'])->name('password.edit');
    Route::post('/cambiar-password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/',           [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/historial',  [HistorialController::class, 'index'])->name('historial');
    Route::get('/adeudos',    [AdeudoController::class, 'index'])->name('adeudos');
    Route::get('/pagos',      [PagosController::class, 'index'])->name('pagos');
    Route::get('/documentos', [DocumentoAlumnoController::class, 'index'])->name('documentos');

    Route::get('/perfil',     [PerfilController::class, 'edit'])->name('perfil');
    Route::put('/perfil',     [PerfilController::class, 'update'])->name('perfil.update');
});
