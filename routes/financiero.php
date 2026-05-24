<?php

use App\Http\Controllers\Financiero\DashboardController;
use App\Http\Controllers\Financiero\FichaPagoController;
use App\Http\Controllers\Financiero\AspiranteFinancieroController;
use App\Http\Controllers\Financiero\ReporteController;
use App\Http\Controllers\Financiero\PasarelaController;
use App\Http\Controllers\Financiero\ComprobanteController;
use App\Http\Controllers\Financiero\PagoAlumnoController;
use Illuminate\Support\Facades\Route;

// Dashboard financiero
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Fichas de pago
Route::resource('fichas', FichaPagoController::class)->except(['edit', 'update']);
Route::patch('fichas/{ficha}/cancelar',  [FichaPagoController::class, 'cancelar'])->name('fichas.cancelar');
Route::patch('fichas/{ficha}/pago-manual', [FichaPagoController::class, 'registrarPagoManual'])->name('fichas.pago-manual');
Route::get('fichas/{ficha}/pdf',         [FichaPagoController::class, 'descargarPdf'])->name('fichas.pdf');

// Aspirantes (vista financiera: datos básicos + estado de pago)
Route::get('/aspirantes',             [AspiranteFinancieroController::class, 'index'])->name('aspirantes.index');
Route::get('/aspirantes/{aspirante}', [AspiranteFinancieroController::class, 'show'])->name('aspirantes.show');
Route::post('/aspirantes/{aspirante}/generar-ficha', [AspiranteFinancieroController::class, 'generarFicha'])
    ->name('aspirantes.generar-ficha');

// Pasarela de pagos — iniciar pago en línea
Route::post('/pago/iniciar/{ficha}', [PasarelaController::class, 'iniciarPago'])->name('pago.iniciar');
Route::get('/pago/exitoso',          [PasarelaController::class, 'pagoExitoso'])->name('pago.exitoso');
Route::get('/pago/cancelado',        [PasarelaController::class, 'pagoCancelado'])->name('pago.cancelado');

// Comprobantes de transferencia bancaria
Route::prefix('comprobantes')->name('comprobantes.')->group(function () {
    Route::get('/',                         [ComprobanteController::class, 'index'])->name('index');
    Route::patch('/{comprobante}/aprobar',  [ComprobanteController::class, 'aprobar'])->name('aprobar');
    Route::patch('/{comprobante}/rechazar', [ComprobanteController::class, 'rechazar'])->name('rechazar');
});

// Pagos de alumnos (adeudos)
Route::prefix('pagos')->name('pagos.')->group(function () {
    Route::get('/',                       [PagoAlumnoController::class, 'index'])->name('index');
    Route::patch('/{adeudo}/validar',     [PagoAlumnoController::class, 'validar'])->name('validar');
    Route::patch('/{adeudo}/rechazar',    [PagoAlumnoController::class, 'rechazar'])->name('rechazar');
});

// Reportes
Route::prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/ingresos',  [ReporteController::class, 'ingresos'])->name('ingresos');
    Route::get('/fichas',    [ReporteController::class, 'fichas'])->name('fichas');
    Route::get('/exportar',  [ReporteController::class, 'exportar'])->name('exportar');
    Route::get('/pdf/{tipo}',[ReporteController::class, 'descargarPdf'])->name('pdf');
});
