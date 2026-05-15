<?php

namespace App\Http\Controllers\Portal\Aspirante;

use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('portal.aspirante.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'credencial' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ], [
            'credencial.required' => 'Ingresa tu folio o correo electrónico.',
            'password.required'   => 'La contraseña es requerida.',
        ]);

        $credencial = trim($request->credencial);

        // Buscar por folio o email
        $aspirante = Aspirante::where('folio', $credencial)
            ->orWhere('email', $credencial)
            ->first();

        if (! $aspirante || ! $aspirante->password || ! Hash::check($request->password, $aspirante->password)) {
            return back()->withErrors([
                'credencial' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('credencial');
        }

        auth('aspirante')->login($aspirante, $request->boolean('remember'));

        $request->session()->regenerate();

        Bitacora::registrar('login_aspirante', "Aspirante {$aspirante->folio} inició sesión en portal");

        return redirect()->route('portal.aspirante.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        auth('aspirante')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.aspirante.login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
