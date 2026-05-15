<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlumnoAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('alumno')->check()) {
            return redirect()->route('portal.alumno.login')
                ->with('error', 'Debes iniciar sesión para acceder al portal.');
        }

        // Forzar cambio de contraseña en primer inicio de sesión
        if (auth('alumno')->user()->must_change_password
            && ! $request->routeIs('portal.alumno.password.*')
        ) {
            return redirect()->route('portal.alumno.password.edit');
        }

        return $next($request);
    }
}
