<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Registra en el log las peticiones POST/PUT/PATCH/DELETE
     * de usuarios autenticados para auditoría.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            auth()->check()
            && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])
            && ! $request->is('webhooks/*')
        ) {
            Log::channel('single')->info('Actividad de usuario', [
                'user_id' => auth()->id(),
                'method'  => $request->method(),
                'url'     => $request->fullUrl(),
                'ip'      => $request->ip(),
                'status'  => $response->getStatusCode(),
            ]);
        }

        return $response;
    }
}
