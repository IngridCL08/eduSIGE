<?php

use App\Http\Controllers\Escolar\DashboardController;
use App\Http\Controllers\Escolar\AspiranteController;
use App\Http\Controllers\Escolar\AlumnoController;
use App\Http\Controllers\Escolar\DocumentoController;
use App\Http\Controllers\Escolar\EstadisticaController;
use App\Http\Controllers\Escolar\ExamenAdmisionController;
use App\Http\Controllers\Escolar\InscripcionController;
use App\Http\Controllers\Escolar\PeriodoController;
use App\Http\Controllers\Escolar\MateriaController;
use App\Http\Controllers\Escolar\AdeudoController;
use App\Http\Controllers\Escolar\CalendarioReinscripcionController;
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

// Inscripción de aspirantes admitidos
Route::prefix('inscripcion')->name('inscripcion.')->group(function () {
    Route::get('/',                              [InscripcionController::class, 'index'])->name('index');
    Route::post('/{aspirante}',                  [InscripcionController::class, 'inscribir'])->name('store');
});

// Períodos escolares
Route::resource('periodos', PeriodoController::class);
Route::patch('periodos/{periodo}/activar',       [PeriodoController::class, 'activar'])->name('periodos.activar');
Route::patch('periodos/{periodo}/cerrar',        [PeriodoController::class, 'cerrar'])->name('periodos.cerrar');
Route::patch('periodos/{periodo}/semana',        [PeriodoController::class, 'avanzarSemana'])->name('periodos.semana');
Route::patch('periodos/{periodo}/calificaciones',[PeriodoController::class, 'toggleCalificaciones'])->name('periodos.calificaciones');

// Catálogo de materias
Route::resource('materias', MateriaController::class);
Route::post('materias/{materia}/plan',              [MateriaController::class, 'planStore'])->name('materias.plan.store');
Route::delete('plan-estudios/{plan}',               [MateriaController::class, 'planDestroy'])->name('materias.plan.destroy');
Route::get('carreras/{carrera}/plan',               [MateriaController::class, 'planIndex'])->name('materias.plan.index');

// Adeudos
Route::resource('adeudos', AdeudoController::class)->except(['edit', 'update']);
Route::patch('adeudos/{adeudo}/liquidar',           [AdeudoController::class, 'liquidar'])->name('adeudos.liquidar');

// Calendario de reinscripción
Route::prefix('calendario')->name('calendario.')->group(function () {
    Route::get('/',                                     [CalendarioReinscripcionController::class, 'index'])->name('index');
    Route::post('/',                                    [CalendarioReinscripcionController::class, 'store'])->name('store');
    Route::put('/{calendario}',                         [CalendarioReinscripcionController::class, 'update'])->name('update');
    Route::patch('/{calendario}/toggle',                [CalendarioReinscripcionController::class, 'toggle'])->name('toggle');
    Route::delete('/{calendario}',                      [CalendarioReinscripcionController::class, 'destroy'])->name('destroy');
});

// Estadísticas escolares
Route::prefix('estadisticas')->name('estadisticas.')->group(function () {
    Route::get('/',            [EstadisticaController::class, 'index'])->name('index');
    Route::get('/aspirantes',  [EstadisticaController::class, 'aspirantes'])->name('aspirantes');
    Route::get('/alumnos',     [EstadisticaController::class, 'alumnos'])->name('alumnos');
    Route::get('/por-carrera', [EstadisticaController::class, 'porCarrera'])->name('carrera');
    Route::get('/json',        [EstadisticaController::class, 'json'])->name('json');
});
