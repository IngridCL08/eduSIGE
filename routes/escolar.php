<?php

use App\Http\Controllers\Escolar\DashboardController;
use App\Http\Controllers\Escolar\AspiranteController;
use App\Http\Controllers\Escolar\AlumnoController;
use App\Http\Controllers\Escolar\DocumentoController;
use App\Http\Controllers\Escolar\EstadisticaController;
use App\Http\Controllers\Escolar\ExamenAdmisionController;
use App\Http\Controllers\Escolar\ComprobanteController;
use App\Http\Controllers\Escolar\InscripcionController;
use Illuminate\Support\Facades\Route;

// Dashboard escolar
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Aspirantes (vista académica completa)
Route::resource('aspirantes', AspiranteController::class);
Route::patch('aspirantes/{aspirante}/estatus', [AspiranteController::class, 'actualizarEstatus'])
    ->name('aspirantes.estatus');
Route::get('aspirantes/{aspirante}/historial', [AspiranteController::class, 'historial'])
    ->name('aspirantes.historial');
Route::get('aspirantes/exportar/excel', [AspiranteController::class, 'exportarExcel'])
    ->name('aspirantes.exportar');

// Documentos — rutas standalone (aspirante_id como campo de formulario o query string)
Route::prefix('documentos')->name('documentos.')->group(function () {
    Route::get('/',                         [DocumentoController::class, 'index'])->name('index');
    Route::post('/',                        [DocumentoController::class, 'store'])->name('store');
    Route::patch('/{documento}/verificar',  [DocumentoController::class, 'verificar'])->name('verificar');
    Route::delete('/{documento}',           [DocumentoController::class, 'destroy'])->name('destroy');
});

// Alumnos
Route::resource('alumnos', AlumnoController::class);
Route::patch('alumnos/{alumno}/estatus', [AlumnoController::class, 'actualizarEstatus'])
    ->name('alumnos.estatus');
Route::get('alumnos/{alumno}/historial-academico', [AlumnoController::class, 'historialAcademico'])
    ->name('alumnos.historial');
Route::post('alumnos/{alumno}/historial-academico', [AlumnoController::class, 'agregarMateriaHistorial'])
    ->name('alumnos.historial.store');
Route::get('alumnos/exportar/excel', [AlumnoController::class, 'exportarExcel'])
    ->name('alumnos.exportar');

// Exámenes de admisión
Route::prefix('examenes')->name('examenes.')->group(function () {
    Route::get('/',                              [ExamenAdmisionController::class, 'index'])->name('index');
    Route::get('/{aspirante}/crear',             [ExamenAdmisionController::class, 'create'])->name('create');
    Route::post('/{aspirante}',                  [ExamenAdmisionController::class, 'store'])->name('store');
    Route::get('/{examen}/editar',               [ExamenAdmisionController::class, 'edit'])->name('edit');
    Route::put('/{examen}',                      [ExamenAdmisionController::class, 'update'])->name('update');
});

// Comprobantes de transferencia
Route::prefix('comprobantes')->name('comprobantes.')->group(function () {
    Route::get('/',                              [ComprobanteController::class, 'index'])->name('index');
    Route::patch('/{comprobante}/aprobar',       [ComprobanteController::class, 'aprobar'])->name('aprobar');
    Route::patch('/{comprobante}/rechazar',      [ComprobanteController::class, 'rechazar'])->name('rechazar');
});

// Inscripción de aspirantes admitidos
Route::prefix('inscripcion')->name('inscripcion.')->group(function () {
    Route::get('/',                              [InscripcionController::class, 'index'])->name('index');
    Route::post('/{aspirante}',                  [InscripcionController::class, 'inscribir'])->name('store');
});

// Estadísticas escolares
Route::prefix('estadisticas')->name('estadisticas.')->group(function () {
    Route::get('/',            [EstadisticaController::class, 'index'])->name('index');
    Route::get('/aspirantes',  [EstadisticaController::class, 'aspirantes'])->name('aspirantes');
    Route::get('/alumnos',     [EstadisticaController::class, 'alumnos'])->name('alumnos');
    Route::get('/por-carrera', [EstadisticaController::class, 'porCarrera'])->name('carrera');
    Route::get('/json',        [EstadisticaController::class, 'json'])->name('json');
});
