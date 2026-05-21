@extends('layouts.escolar')

@section('title', 'Calendario de Reinscripción')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Calendario de Reinscripción</span>
@endsection

@section('content')

<div class="card mb-5 p-4">
    <form method="GET" class="flex items-center gap-3 flex-wrap">
        <select name="periodo_id" class="form-select w-56">
            <option value="">— Todos los períodos —</option>
            @foreach($periodos as $p)
            <option value="{{ $p->id }}" {{ request('periodo_id') == $p->id ? 'selected' : '' }}>
                {{ $p->nombre }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary btn-sm">Filtrar</button>
        <a href="{{ route('escolar.calendario.index') }}" class="btn-secondary btn-sm">Limpiar</a>
    </form>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Tabla de ventanas --}}
    <div class="xl:col-span-2 card p-0">
        <div class="card-header px-6 pt-5 pb-4">
            <h3 class="card-title">
                Ventanas de Reinscripción
                @if($periodo)
                    <span class="text-sm font-normal text-carbon-500">— {{ $periodo->nombre }}</span>
                @endif
            </h3>
        </div>
        <div class="table-wrapper rounded-t-none border-t-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Carrera / Semestre</th>
                        <th>Período</th>
                        <th>Turno</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th class="text-center">Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($calendarios as $cal)
                    <tr>
                        <td>
                            <p class="font-medium text-carbon-950">{{ $cal->carrera->nombre ?? '—' }}</p>
                            <p class="text-xs text-carbon-400">Semestre {{ $cal->semestre }}°</p>
                        </td>
                        <td class="text-sm text-carbon-600">{{ $cal->periodo->nombre ?? '—' }}</td>
                        <td>
                            <span class="badge-info badge text-xs capitalize">{{ $cal->turno }}</span>
                        </td>
                        <td class="text-xs text-carbon-700">
                            {{ \Carbon\Carbon::parse($cal->fecha_hora_inicio)->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-xs text-carbon-700">
                            {{ \Carbon\Carbon::parse($cal->fecha_hora_fin)->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-center">
                            @php $estado = $cal->estado; @endphp
                            <span class="{{ $estado['color'] }} text-xs">{{ $estado['label'] }}</span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <form method="POST" action="{{ route('escolar.calendario.toggle', $cal) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="{{ $cal->activo ? 'btn-danger' : 'btn-success' }} btn-sm">
                                        {{ $cal->activo ? 'Suspender' : 'Activar' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('escolar.calendario.destroy', $cal) }}"
                                      onsubmit="return confirm('¿Eliminar esta ventana?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-secondary btn-sm">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-10 text-carbon-400">No hay ventanas configuradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Formulario nueva ventana --}}
    <div class="card">
        <h3 class="card-title mb-4">Nueva ventana</h3>
        <form method="POST" action="{{ route('escolar.calendario.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="form-label">Período <span class="text-danger">*</span></label>
                <select name="periodo_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($periodos as $p)
                    <option value="{{ $p->id }}" {{ (request('periodo_id') ?? optional($periodo)->id) == $p->id ? 'selected' : '' }}>
                        {{ $p->nombre }}
                    </option>
                    @endforeach
                </select>
                @error('periodo_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Carrera <span class="text-danger">*</span></label>
                <select name="carrera_id" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($carreras as $carrera)
                    <option value="{{ $carrera->id }}">{{ $carrera->clave }} — {{ $carrera->nombre }}</option>
                    @endforeach
                </select>
                @error('carrera_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Semestre <span class="text-danger">*</span></label>
                    <select name="semestre" class="form-select" required>
                        @for($i = 2; $i <= 9; $i++)
                        <option value="{{ $i }}">{{ $i }}°</option>
                        @endfor
                    </select>
                    @error('semestre')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Turno <span class="text-danger">*</span></label>
                    <select name="turno" class="form-select" required>
                        <option value="todos">Todos</option>
                        <option value="matutino">Matutino</option>
                        <option value="vespertino">Vespertino</option>
                        <option value="mixto">Mixto</option>
                    </select>
                    @error('turno')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label">Apertura <span class="text-danger">*</span></label>
                <input type="datetime-local" name="fecha_hora_inicio" class="form-input"
                       value="{{ old('fecha_hora_inicio') }}" required>
                @error('fecha_hora_inicio')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Cierre <span class="text-danger">*</span></label>
                <input type="datetime-local" name="fecha_hora_fin" class="form-input"
                       value="{{ old('fecha_hora_fin') }}" required>
                @error('fecha_hora_fin')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Instrucciones</label>
                <textarea name="instrucciones" rows="2" class="form-input text-sm"
                          placeholder="Indicaciones para los alumnos...">{{ old('instrucciones') }}</textarea>
            </div>

            <button type="submit" class="btn-primary w-full">Agregar ventana</button>
        </form>
    </div>

</div>

@endsection
