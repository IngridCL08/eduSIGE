<?php

namespace App\Http\Controllers\Portal\Aspirante;

use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\Carrera;
use App\Models\Periodo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegistroController extends Controller
{
    public function create(): View
    {
        $carreras = Carrera::activas()->orderBy('nombre')->get();
        $periodos = Periodo::orderByDesc('anio')->get();
        return view('portal.aspirante.registro', compact('carreras', 'periodos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:100'],
            'apellido_paterno' => ['required', 'string', 'max:100'],
            'apellido_materno' => ['nullable', 'string', 'max:100'],
            'email'            => ['required', 'email', 'max:191', 'unique:aspirantes,email'],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'carrera_id'       => ['required', 'exists:carreras,id'],
            'periodo_id'       => ['required', 'exists:periodos,id'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique'      => 'Este correo ya está registrado. Inicia sesión o usa otro correo.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'=> 'Las contraseñas no coinciden.',
        ]);

        $anio   = now()->year;
        $count  = Aspirante::whereYear('created_at', '=', $anio)->count();
        $data['folio']    = 'ASP-' . $anio . '-' . str_pad((string) ($count + 1), 6, '0', STR_PAD_LEFT);
        $data['password'] = Hash::make($request->password);
        $data['status']   = Aspirante::STATUS_REGISTRADO;

        $aspirante = Aspirante::create($data);

        auth('aspirante')->login($aspirante);
        $request->session()->regenerate();

        return redirect()->route('portal.aspirante.dashboard')
            ->with('success', "Registro completado. Tu folio es {$aspirante->folio}. ¡Bienvenido!");
    }
}
