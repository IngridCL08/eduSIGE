<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión — eduSIGE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-navy-950 flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 bg-navy-800 rounded-2xl items-center justify-center mb-4 shadow-lg">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">eduSIGE</h1>
            <p class="text-slate-400 text-sm mt-1">{{ config('app.edusige.institucion') }}</p>
        </div>

        {{-- Card de login --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-lg font-semibold text-carbon-950 mb-1">Iniciar sesión</h2>
            <p class="text-sm text-carbon-500 mb-6">Ingresa tus credenciales para acceder al sistema.</p>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="alert-danger mb-5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input id="email" name="email" type="email"
                           value="{{ old('email') }}"
                           autocomplete="email"
                           required
                           class="form-input @error('email') border-danger @enderror"
                           placeholder="usuario@ejemplo.com">
                </div>

                {{-- Contraseña --}}
                <div x-data="{ show: false }">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="relative">
                        <input id="password" name="password"
                               :type="show ? 'text' : 'password'"
                               autocomplete="current-password"
                               required
                               class="form-input pr-10 @error('password') border-danger @enderror"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-carbon-400 hover:text-carbon-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Recordarme --}}
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="w-4 h-4 text-navy-800 border-carbon-300 rounded">
                    <label for="remember" class="ml-2 text-sm text-carbon-600">Mantener sesión iniciada</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary w-full justify-center py-2.5 mt-2">
                    Iniciar sesión
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            eduSIGE &copy; {{ date('Y') }} — Sistema Integral de Gestión Educativa
        </p>
    </div>

</body>
</html>
