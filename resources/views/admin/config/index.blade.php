@extends('layouts.admin')
@section('title','Configuración del Sistema')

@section('content')
<div class="max-w-xl">
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Configuración General de eduSIGE</h3>
    </div>
    <form method="POST" action="{{ route('admin.config.update') }}" class="space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="form-label">Nombre de la Institución <span class="text-danger">*</span></label>
            <input name="institucion" value="{{ old('institucion', $config['institucion']) }}"
                   class="form-input @error('institucion') border-danger @enderror" required>
            @error('institucion')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Monto de Ficha (MXN) <span class="text-danger">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-carbon-400 text-sm">$</span>
                    <input name="monto_ficha" type="number" step="0.01" min="1"
                           value="{{ old('monto_ficha', $config['monto_ficha']) }}"
                           class="form-input pl-7 @error('monto_ficha') border-danger @enderror" required>
                </div>
                @error('monto_ficha')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Días de vigencia de ficha <span class="text-danger">*</span></label>
                <input name="dias_vigencia_ficha" type="number" min="1" max="30"
                       value="{{ old('dias_vigencia_ficha', $config['dias_vigencia_ficha']) }}"
                       class="form-input @error('dias_vigencia_ficha') border-danger @enderror" required>
                <p class="form-help">Días antes que expire la ficha</p>
                @error('dias_vigencia_ficha')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <p class="text-sm text-amber-800">
                <strong>Nota:</strong> Los cambios se aplicarán inmediatamente en el sistema. Asegúrate de que los datos sean correctos antes de guardar.
            </p>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Guardar Configuración
            </button>
        </div>
    </form>
</div>

{{-- Info del entorno --}}
<div class="card mt-5">
    <div class="card-header"><h3 class="card-title">Información del Sistema</h3></div>
    <div class="space-y-2 text-sm">
        @foreach([
            ['PHP', PHP_VERSION],
            ['Laravel', app()->version()],
            ['Entorno', config('app.env')],
            ['Debug', config('app.debug') ? 'Activo' : 'Inactivo'],
            ['Zona horaria', config('app.timezone')],
        ] as [$label, $val])
        <div class="flex justify-between py-2 border-b border-carbon-100 last:border-0">
            <span class="text-carbon-500">{{ $label }}</span>
            <code class="text-xs bg-carbon-100 px-2 py-0.5 rounded">{{ $val }}</code>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection
