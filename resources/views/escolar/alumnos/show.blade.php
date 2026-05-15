@extends('layouts.escolar')
@section('title','Alumno — ' . $alumno->matricula)

@section('breadcrumb')
    <a href="{{ route('escolar.alumnos.index') }}">Alumnos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ $alumno->matricula }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('escolar.alumnos.edit', $alumno) }}" class="btn-secondary btn-sm">Editar</a>
    <a href="{{ route('escolar.alumnos.historial', $alumno) }}" class="btn-outline btn-sm">Historial Académico</a>
@endsection

@section('content')

{{-- Credenciales de inscripción (alumno recién creado) --}}
@if(session('inscripcion_exitosa'))
@php $datos = session('inscripcion_exitosa'); @endphp
<div class="bg-blue-50 border border-blue-300 rounded-xl p-5 mb-6">
    <p class="font-semibold text-blue-800 mb-2">✓ Inscripción completada — Credenciales del Portal Alumno</p>
    <div class="bg-white rounded-lg border border-blue-200 p-4 font-mono text-sm space-y-1">
        <p><span class="text-slate-500">Nombre:</span>
           <span class="font-bold text-slate-900">{{ $datos['nombre'] }}</span></p>
        <p><span class="text-slate-500">Matrícula:</span>
           <span class="font-bold text-slate-900">{{ $datos['matricula'] }}</span></p>
        <p><span class="text-slate-500">Contraseña inicial:</span>
           <span class="font-bold text-slate-900">{{ $datos['password'] }}</span></p>
    </div>
    <p class="text-xs text-blue-600 mt-2">⚠ Anota estas credenciales. No se volverán a mostrar.</p>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Columna principal --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Datos personales --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Datos del Alumno</h3>
                @php
                    $statusClasses = ['activo'=>'badge-success','baja_temporal'=>'badge-warning','baja_definitiva'=>'badge-danger','egresado'=>'badge-info','titulado'=>'badge-neutral'];
                    $statusLabels  = ['activo'=>'Activo','baja_temporal'=>'Baja Temporal','baja_definitiva'=>'Baja Definitiva','egresado'=>'Egresado','titulado'=>'Titulado'];
                @endphp
                <span class="{{ $statusClasses[$alumno->status] ?? 'badge-neutral' }} badge">
                    {{ $statusLabels[$alumno->status] ?? $alumno->status }}
                </span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                @foreach([
                    ['Matrícula',     $alumno->matricula],
                    ['Nombre',        $alumno->aspirante->nombre_completo],
                    ['CURP',          $alumno->aspirante->curp ?? '—'],
                    ['Email',         $alumno->aspirante->email],
                    ['Teléfono',      $alumno->aspirante->telefono ?? '—'],
                    ['Sexo',          $alumno->aspirante->sexo === 'M' ? 'Masculino' : ($alumno->aspirante->sexo === 'F' ? 'Femenino' : 'Otro')],
                    ['Carrera',       $alumno->carrera?->nombre ?? '—'],
                    ['Período ingreso',$alumno->periodoIngreso?->nombre ?? '—'],
                    ['Promedio',      $alumno->promedio_general ? number_format($alumno->promedio_general, 2) : '—'],
                    ['Créditos',      ($alumno->creditos_acumulados ?? 0) . ' créditos'],
                ] as [$label, $val])
                <div>
                    <p class="text-carbon-500 text-xs mb-0.5">{{ $label }}</p>
                    <p class="font-medium text-carbon-900 break-all">{{ $val }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Historial académico reciente --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Historial Académico</h3>
                <a href="{{ route('escolar.alumnos.historial', $alumno) }}" class="btn-outline btn-sm">Ver completo</a>
            </div>
            @if($alumno->historial->count())
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Clave</th>
                            <th>Materia</th>
                            <th>Créditos</th>
                            <th>Período</th>
                            <th>Calificación</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumno->historial()->latest()->take(10)->get() as $h)
                        @php
                            $hClasses = ['cursando'=>'badge-warning','acreditada'=>'badge-success','reprobada'=>'badge-danger','baja'=>'badge-neutral'];
                            $hLabels  = ['cursando'=>'Cursando','acreditada'=>'Acreditada','reprobada'=>'Reprobada','baja'=>'Baja'];
                        @endphp
                        <tr>
                            <td class="font-mono text-xs">{{ $h->clave_materia ?? '—' }}</td>
                            <td class="font-medium text-sm">{{ $h->materia }}</td>
                            <td>{{ $h->creditos }}</td>
                            <td class="text-xs text-carbon-500">{{ $h->periodo?->nombre ?? '—' }}</td>
                            <td class="font-bold {{ $h->calificacion >= 6 ? 'text-green-700' : 'text-red-600' }}">
                                {{ $h->calificacion !== null ? number_format($h->calificacion, 1) : '—' }}
                            </td>
                            <td>
                                <span class="{{ $hClasses[$h->status] ?? 'badge-neutral' }} badge">
                                    {{ $hLabels[$h->status] ?? $h->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-sm text-carbon-400 py-6 text-center">Sin materias registradas en el historial.</p>
            @endif
        </div>

    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">

        {{-- Avatar y datos rápidos --}}
        <div class="card">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-navy-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-navy-800 font-black text-lg">
                        {{ strtoupper(substr($alumno->aspirante->nombre, 0, 1) . substr($alumno->aspirante->apellido_paterno, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="font-bold text-carbon-900">{{ $alumno->aspirante->nombre_completo }}</p>
                    <p class="font-mono text-xs text-carbon-400">{{ $alumno->matricula }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-navy-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-black text-navy-800">
                        {{ $alumno->promedio_general ? number_format($alumno->promedio_general, 1) : '—' }}
                    </p>
                    <p class="text-xs text-navy-600 mt-0.5">Promedio</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-black text-green-800">{{ $alumno->creditos_acumulados ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-0.5">Créditos</p>
                </div>
            </div>
        </div>

        {{-- Documentos --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Documentos</h3>
                <a href="{{ route('escolar.documentos.index', ['aspirante_id' => $alumno->aspirante_id]) }}"
                   class="btn-outline btn-sm">Gestionar</a>
            </div>
            @php
                $docs = $alumno->aspirante->documentos;
                $verificados = $docs->where('verificado', true)->count();
            @endphp
            <div class="flex items-center gap-3">
                <div class="flex-1 bg-carbon-100 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full"
                         style="width: {{ $docs->count() ? round($verificados/$docs->count()*100) : 0 }}%"></div>
                </div>
                <span class="text-sm font-medium">{{ $verificados }}/{{ $docs->count() }}</span>
            </div>
            <p class="text-xs text-carbon-400 mt-1">Documentos verificados</p>
        </div>

        {{-- Cambio de estatus --}}
        <div class="card" x-data="{ open: false }">
            <div class="card-header">
                <h3 class="card-title">Cambiar Estatus</h3>
                <button @click="open = !open" class="btn-outline btn-sm">
                    <span x-text="open ? 'Cerrar' : 'Cambiar'"></span>
                </button>
            </div>
            <div x-show="open" x-transition>
                <form method="POST" action="{{ route('escolar.alumnos.estatus', $alumno) }}" class="space-y-3 mt-3">
                    @csrf @method('PATCH')
                    <div>
                        <label class="form-label">Nuevo Estatus <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            @foreach(['activo'=>'Activo','baja_temporal'=>'Baja Temporal','baja_definitiva'=>'Baja Definitiva','egresado'=>'Egresado','titulado'=>'Titulado'] as $k=>$v)
                            <option value="{{ $k }}" {{ $alumno->status === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" rows="2" class="form-textarea"></textarea>
                    </div>
                    <button type="submit" class="btn-warning w-full justify-center">Actualizar Estatus</button>
                </form>
            </div>
        </div>

        {{-- Enlace a aspirante --}}
        <a href="{{ route('escolar.aspirantes.show', $alumno->aspirante) }}"
           class="btn-outline w-full justify-center">Ver Expediente de Aspirante</a>

    </div>
</div>
@endsection
