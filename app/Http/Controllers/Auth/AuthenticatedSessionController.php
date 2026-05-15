<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Bitacora;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'El correo electrónico es requerido.',
            'email.email'       => 'Ingrese un correo electrónico válido.',
            'password.required' => 'La contraseña es requerida.',
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        // Verificar que el usuario esté activo
        if (! $user->activo) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Tu cuenta se encuentra desactivada. Contacta al administrador.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Actualizar último acceso
        $user->update(['ultimo_acceso' => now()]);

        // Registrar en bitácora
        Bitacora::registrar('login', 'Inicio de sesión');

        return redirect()->intended(route($user->dashboardRoute()));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Bitacora::registrar('logout', 'Cierre de sesión');

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
