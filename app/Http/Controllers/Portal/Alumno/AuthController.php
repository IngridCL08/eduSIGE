<?php

namespace App\Http\Controllers\Portal\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('portal.alumno.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'matricula' => ['required', 'string'],
            'password'  => ['required', 'string'],
        ], [
            'matricula.required' => 'Ingresa tu número de matrícula.',
            'password.required'  => 'La contraseña es requerida.',
        ]);

        $alumno = Alumno::where('matricula', trim($request->matricula))->first();

        if (! $alumno || ! $alumno->password || ! Hash::check($request->password, $alumno->password)) {
            return back()->withErrors([
                'matricula' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('matricula');
        }

        if ($alumno->status === Alumno::STATUS_BAJA_DEFINITIVA) {
            return back()->withErrors([
                'matricula' => 'Tu cuenta está dada de baja. Contacta a Control Escolar.',
            ])->onlyInput('matricula');
        }

        auth('alumno')->login($alumno, $request->boolean('remember'));
        $request->session()->regenerate();

        Bitacora::registrar('login_alumno', "Alumno {$alumno->matricula} inició sesión en portal");

        return redirect()->route('portal.alumno.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        auth('alumno')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.alumno.login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
