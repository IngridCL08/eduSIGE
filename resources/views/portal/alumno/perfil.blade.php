@extends('layouts.portal-alumno')
@section('title', 'Mi Perfil')

@section('content')

<h2 class="text-xl font-bold text-slate-800 mb-6">Mi Perfil</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- Datos académicos (solo lectura) --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Datos académicos</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-slate-400">Matrícula</dt>
                <dd class="font-mono font-bold text-slate-800">{{ $alumno->matricula }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Nombre completo</dt>
                <dd class="font-medium text-slate-800 text-right max-w-[200px]">{{ $alumno->nombre_completo }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Carrera</dt>
                <dd class="font-medium text-slate-800 text-right max-w-[200px]">{{ $alumno->carrera?->nombre ?? '—' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Período de ingreso</dt>
                <dd class="text-slate-800">{{ $alumno->periodoIngreso?->nombre ?? '—' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-400">Estatus</dt>
                <dd><span class="badge {{ $alumno->status_color }}">{{ $alumno->status_nombre }}</span></dd>
            </div>
        </dl>
    </div>

    {{-- Datos de contacto (editables) --}}
    @php $asp = $alumno->aspirante; @endphp
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Datos de contacto</h3>

        <form method="POST" action="{{ route('portal.alumno.perfil.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $asp?->telefono) }}"
                       class="form-input" placeholder="10 dígitos">
            </div>
            <div>
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" value="{{ old('direccion', $asp?->direccion) }}"
                       class="form-input" placeholder="Calle y número">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Colonia</label>
                    <input type="text" name="colonia" value="{{ old('colonia', $asp?->colonia) }}"
                           class="form-input">
                </div>
                <div>
                    <label class="form-label">C.P.</label>
                    <input type="text" name="cp" value="{{ old('cp', $asp?->cp) }}"
                           class="form-input" maxlength="5">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Municipio</label>
                    <input type="text" name="municipio" value="{{ old('municipio', $asp?->municipio) }}"
                           class="form-input">
                </div>
                <div>
                    <label class="form-label">Estado</label>
                    <input type="text" name="estado" value="{{ old('estado', $asp?->estado) }}"
                           class="form-input">
                </div>
            </div>
            <button type="submit" class="btn-primary w-full justify-center"
                    style="background-color:#059669">
                Guardar cambios
            </button>
        </form>
    </div>

</div>

@endsection
