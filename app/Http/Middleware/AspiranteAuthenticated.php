<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AspiranteAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('aspirante')->check()) {
            return redirect()->route('portal.aspirante.login')
                ->with('error', 'Debes iniciar sesión para acceder al portal.');
        }

        return $next($request);
    }
}
