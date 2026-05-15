<?php

namespace App\Http\Controllers\Portal\Alumno;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function edit(): View
    {
        $alumno = auth('alumno')->user();
        $alumno->load(['carrera', 'periodoIngreso', 'aspirante']);

        return view('portal.alumno.perfil', compact('alumno'));
    }

    public function update(Request $request): RedirectResponse
    {
        $alumno    = auth('alumno')->user();
        $aspirante = $alumno->aspirante;

        $data = $request->validate([
            'telefono'  => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'colonia'   => ['nullable', 'string', 'max:150'],
            'municipio' => ['nullable', 'string', 'max:150'],
            'estado'    => ['nullable', 'string', 'max:100'],
            'cp'        => ['nullable', 'string', 'max:10'],
        ]);

        $aspirante->update($data);

        return back()->with('success', 'Datos actualizados correctamente.');
    }
}
