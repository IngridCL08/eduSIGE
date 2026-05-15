<!DOCTYPE html>
<html lang="es" class="h-full bg-emerald-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Alumnos — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center px-4">

<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-emerald-700">{{ config('app.name') }}</h1>
        <p class="text-slate-500 mt-1">Portal de Alumnos</p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Iniciar sesión</h2>

        @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('portal.alumno.login.post') }}" class="space-y-5">
            @csrf

            <div>
                <label class="form-label">Número de matrícula</label>
                <input type="text" name="matricula" value="{{ old('matricula') }}"
                       class="form-input @error('matricula') border-red-400 @enderror"
                       placeholder="2026ISC0001"
                       autofocus autocomplete="username">
                @error('matricula')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">Contraseña</label>
                <input type="password" name="password"
                       class="form-input @error('password') border-red-400 @enderror"
                       autocomplete="current-password">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300">
                <label for="remember" class="text-sm text-slate-600">Mantener sesión iniciada</label>
            </div>

            <button type="submit" class="btn-primary w-full justify-center" style="background-color:#059669">
                Ingresar al portal
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-slate-500 mt-4">
        Tu matrícula y contraseña inicial fueron asignadas por Control Escolar al inscribirte.
    </p>
</div>

</body>
</html>
