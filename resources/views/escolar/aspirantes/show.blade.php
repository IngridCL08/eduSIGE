@extends('layouts.escolar')
@section('title', $aspirante->nombre_completo)

@section('breadcrumb')
    <a href="{{ route('escolar.aspirantes.index') }}">Aspirantes</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ $aspirante->folio }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('escolar.aspirantes.edit', $aspirante) }}" class="btn-secondary btn-sm">Editar</a>
    @if($aspirante->status === \App\Models\Aspirante::STATUS_ADMITIDO && ! $aspirante->alumno)
    <form method="POST" action="{{ route('escolar.inscripcion.store', $aspirante) }}"
          onsubmit="return confirm('¿Inscribir a {{ $aspirante->nombre_completo }}? Se generará su matrícula y contraseña.')">
        @csrf
        <button class="btn-success btn-sm">Inscribir como Alumno</button>
    </form>
    @endif
@endsection

@section('content')

{{-- Credenciales de acceso al portal (se muestra solo al registrar) --}}
@if(session('password_inicial'))
<div class="bg-green-50 border border-green-300 rounded-xl p-5 mb-6">
    <p class="font-semibold text-green-800 mb-2">✓ Aspirante registrado — Credenciales de acceso al portal</p>
    <div class="bg-white rounded-lg border border-green-200 p-4 font-mono text-sm space-y-1">
        <p><span class="text-slate-500">Folio:</span>
           <span class="font-bold text-slate-900">{{ $aspirante->folio }}</span></p>
        <p><span class="text-slate-500">Email:</span>
           <span class="font-bold text-slate-900">{{ $aspirante->email }}</span></p>
        <p><span class="text-slate-500">Contraseña inicial:</span>
           <span class="font-bold text-slate-900">{{ session('password_inicial') }}</span></p>
    </div>
    <p class="text-xs text-green-600 mt-2">⚠ Anota estas credenciales. No se volverán a mostrar.</p>
</div>
@endif

{{-- Credenciales de inscripción (alumno creado) --}}
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
                <h3 class="card-title">Datos Personales</h3>
                <span class="badge {{ $aspirante->status_color }}">{{ $aspirante->status_nombre }}</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                @foreach([
                    ['Folio',         $aspirante->folio],
                    ['Nombre completo',$aspirante->nombre_completo],
                    ['CURP',          $aspirante->curp ?? '—'],
                    ['Sexo',          $aspirante->sexo === 'M' ? 'Masculino' : ($aspirante->sexo === 'F' ? 'Femenino' : 'Otro')],
                    ['Nacimiento',    $aspirante->fecha_nacimiento?->format('d/m/Y') ?? '—'],
                    ['Email',         $aspirante->email],
                    ['Teléfono',      $aspirante->telefono ?? '—'],
                    ['Domicilio',     $aspirante->domicilio ?? '—'],
                    ['Bachillerato',  $aspirante->bachillerato ?? '—'],
                    ['Promedio Bach.',($aspirante->promedio_bachillerato ? number_format($aspirante->promedio_bachillerato,2) : '—')],
                ] as [$label, $val])
                <div>
                    <p class="text-carbon-500 text-xs mb-0.5">{{ $label }}</p>
                    <p class="font-medium text-carbon-900 break-all">{{ $val }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Documentos --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Documentos</h3>
                <a href="{{ route('escolar.documentos.index', ['aspirante_id' => $aspirante->id]) }}"
                   class="btn-outline btn-sm">Gestionar</a>
            </div>
            @if($aspirante->documentos->count())
            <div class="space-y-2">
                @foreach($aspirante->documentos as $doc)
                <div class="flex items-center justify-between py-2 border-b border-carbon-100 last:border-0">
                    <div class="flex items-center gap-2">
                        @if($doc->verificado)
                        <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        @else
                        <span class="w-5 h-5 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-carbon-800">{{ $doc->tipo_nombre }}</p>
                            <p class="text-xs text-carbon-400">{{ $doc->nombre_archivo }}</p>
                        </div>
                    </div>
                    <span class="text-xs {{ $doc->verificado ? 'text-green-600' : 'text-amber-600' }}">
                        {{ $doc->verificado ? 'Verificado' : 'Pendiente' }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-carbon-400 py-4 text-center">Sin documentos cargados.</p>
            @endif
        </div>

        {{-- Historial de estatus --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Historial de Cambios</h3>
                <a href="{{ route('escolar.aspirantes.historial', $aspirante) }}"
                   class="btn-outline btn-sm">Ver completo</a>
            </div>
            <p class="text-sm text-carbon-500">Registro de cambios de estado del aspirante.</p>
        </div>

    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">

        {{-- Info académica --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Información Académica</h3></div>
            <div class="space-y-3">
                @foreach([
                    ['Carrera',  $aspirante->carrera?->nombre ?? '—'],
                    ['Clave',    $aspirante->carrera?->clave ?? '—'],
                    ['Período',  $aspirante->periodo?->nombre ?? '—'],
                    ['Prom. Bach.', $aspirante->promedio_bachillerato ? number_format($aspirante->promedio_bachillerato, 2) : '—'],
                ] as [$l, $v])
                <div class="flex justify-between py-2 border-b border-carbon-100 last:border-0">
                    <span class="text-sm text-carbon-500">{{ $l }}</span>
                    <span class="font-medium text-sm text-right max-w-[55%]">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Ficha de pago --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Ficha de Pago</h3></div>
            @php $ficha = $aspirante->fichas()->latest()->first(); @endphp
            @if($ficha)
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-1.5 border-b border-carbon-100">
                    <span class="text-carbon-500">Folio</span>
                    <span class="font-mono font-semibold">{{ $ficha->folio_ficha }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-carbon-100">
                    <span class="text-carbon-500">Estado</span>
                    <span class="badge {{ $ficha->status_color }}">{{ $ficha->status_nombre }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-carbon-100">
                    <span class="text-carbon-500">Monto</span>
                    <span class="font-bold">{{ $ficha->monto_formateado }}</span>
                </div>
                <div class="flex justify-between py-1.5">
                    <span class="text-carbon-500">Vencimiento</span>
                    <span>{{ $ficha->fecha_vencimiento->format('d/m/Y') }}</span>
                </div>
            </div>
            @else
            <p class="text-sm text-carbon-400 py-3 text-center">Sin ficha registrada.</p>
            @endif
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
                <form method="POST" action="{{ route('escolar.aspirantes.estatus', $aspirante) }}" class="space-y-3 mt-3">
                    @csrf @method('PATCH')
                    <div>
                        <label class="form-label">Nuevo Estatus <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="">— Seleccionar —</option>
                            @foreach(\App\Models\Aspirante::statuses() as $key => $label)
                            <option value="{{ $key }}" {{ $aspirante->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" rows="2" class="form-textarea" placeholder="Motivo del cambio…"></textarea>
                    </div>
                    <button type="submit" class="btn-warning w-full justify-center">Actualizar Estatus</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
