<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — eduSIGE
|--------------------------------------------------------------------------
| Raíz: redirige según el rol del usuario autenticado.
| Cada grupo de módulos tiene su propio archivo de rutas incluido aquí.
*/

// ─── Raíz ─────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->dashboardRoute());
    }
    return redirect()->route('login');
});

// ─── Autenticación (Laravel Breeze) ───────────────────────────
require __DIR__.'/auth.php';

// ─── Módulo: Super Administrador ──────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));

// ─── Módulo: Recursos Financieros ─────────────────────────────
Route::middleware(['auth', 'role:financiero|admin'])
    ->prefix('financiero')
    ->name('financiero.')
    ->group(base_path('routes/financiero.php'));

// ─── Módulo: Control Escolar ──────────────────────────────────
Route::middleware(['auth', 'role:escolar|admin'])
    ->prefix('escolar')
    ->name('escolar.')
    ->group(base_path('routes/escolar.php'));

// ─── Portal: Aspirantes ───────────────────────────────────────
Route::prefix('portal/aspirante')
    ->name('portal.aspirante.')
    ->group(base_path('routes/portal-aspirante.php'));

// ─── Portal: Alumnos ──────────────────────────────────────────
Route::prefix('portal/alumno')
    ->name('portal.alumno.')
    ->group(base_path('routes/portal-alumno.php'));

// ─── Webhooks de pasarelas de pago (sin autenticación web) ────
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('/conekta', [\App\Http\Controllers\Financiero\PasarelaController::class, 'webhookConekta'])
        ->name('conekta');
    Route::post('/paypal', [\App\Http\Controllers\Financiero\PasarelaController::class, 'webhookPaypal'])
        ->name('paypal');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
