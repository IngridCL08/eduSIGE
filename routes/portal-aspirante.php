<?php

use App\Http\Controllers\Portal\Aspirante\AuthController;
use App\Http\Controllers\Portal\Aspirante\DashboardController;
use App\Http\Controllers\Portal\Aspirante\FichaController;
use App\Http\Controllers\Portal\Aspirante\DocumentoPortalController;
use App\Http\Controllers\Portal\Aspirante\RegistroController;
use Illuminate\Support\Facades\Route;

// ─── Login / Logout / Registro (sin auth) ─────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
Route::post('/registro',[RegistroController::class, 'store'])->name('registro.store');

// ─── Rutas protegidas ─────────────────────────────────────────
Route::middleware('auth.aspirante')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/',            [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ficha',       [FichaController::class, 'show'])->name('ficha');
    Route::post('/ficha/pagar',[FichaController::class, 'iniciarPago'])->name('ficha.pagar');
    Route::get('/ficha/exitoso',   [FichaController::class, 'exitoso'])->name('ficha.exitoso');
    Route::get('/ficha/cancelado', [FichaController::class, 'cancelado'])->name('ficha.cancelado');

    Route::post('/ficha/comprobante', [FichaController::class, 'subirComprobante'])
         ->name('ficha.comprobante');

    Route::get('/documentos',  [DocumentoPortalController::class, 'index'])->name('documentos');
    Route::post('/documentos', [DocumentoPortalController::class, 'store'])->name('documentos.store');

    Route::get('/examen',      [DashboardController::class, 'examen'])->name('examen');
    Route::get('/perfil',      [DashboardController::class, 'perfil'])->name('perfil');
});
