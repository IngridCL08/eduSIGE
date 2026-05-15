<!DOCTYPE html>
<html lang="es" class="h-full bg-indigo-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Aspirante — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex items-center justify-center px-4 py-10">

<div class="w-full max-w-lg">
    {{-- Encabezado --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-indigo-700">{{ config('app.name') }}</h1>
        <p class="text-slate-500 mt-1">Portal de Aspirantes — Registro en línea</p>
    </div>

    @if ($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Crear cuenta de aspirante</h2>

        <form method="POST" action="{{ route('portal.aspirante.registro.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nombre(s) <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                           class="form-input @error('nombre') border-red-400 @enderror"
                           autofocus>
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Apellido paterno <span class="text-red-500">*</span></label>
                    <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                           class="form-input @error('apellido_paterno') border-red-400 @enderror">
                    @error('apellido_paterno')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Apellido materno</label>
                <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                       class="form-input @error('apellido_materno') border-red-400 @enderror">
            </div>

            <div>
                <label class="form-label">Correo electrónico <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-input @error('email') border-red-400 @enderror"
                       autocomplete="email">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">Teléfono</label>
                <input type="tel" name="telefono" value="{{ old('telefono') }}"
                       class="form-input" placeholder="10 dígitos">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Carrera de interés <span class="text-red-500">*</span></label>
                    <select name="carrera_id" class="form-select @error('carrera_id') border-red-400 @enderror" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($carreras as $c)
                        <option value="{{ $c->id }}" {{ old('carrera_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('carrera_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Período de ingreso <span class="text-red-500">*</span></label>
                    <select name="periodo_id" class="form-select @error('periodo_id') border-red-400 @enderror" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($periodos as $p)
                        <option value="{{ $p->id }}" {{ old('periodo_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('periodo_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Contraseña <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                       class="form-input @error('password') border-red-400 @enderror"
                       autocomplete="new-password"
                       placeholder="Mínimo 8 caracteres">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">Confirmar contraseña <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation"
                       class="form-input"
                       autocomplete="new-password">
            </div>

            <button type="submit" class="btn-primary w-full justify-center mt-2">
                Crear cuenta y continuar
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-slate-500 mt-4">
        ¿Ya tienes cuenta?
        <a href="{{ route('portal.aspirante.login') }}" class="text-indigo-600 hover:underline font-medium">
            Inicia sesión aquí
        </a>
    </p>
</div>

</body>
</html>
