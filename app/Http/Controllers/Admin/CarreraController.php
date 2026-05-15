<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = Carrera::withCount(['aspirantes', 'alumnos'])->orderBy('nombre')->paginate(20);
        return view('admin.carreras.index', compact('carreras'));
    }

    public function create()
    {
        return view('admin.carreras.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'clave'       => ['required', 'string', 'max:20', 'unique:carreras,clave'],
            'nombre'      => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string'],
        ]);

        Carrera::create($request->only('clave', 'nombre', 'descripcion') + ['activa' => true]);

        return redirect()->route('admin.carreras.index')->with('success', 'Carrera creada correctamente.');
    }

    public function edit(Carrera $carrera)
    {
        return view('admin.carreras.edit', compact('carrera'));
    }

    public function update(Request $request, Carrera $carrera): RedirectResponse
    {
        $request->validate([
            'clave'       => ['required', 'string', 'max:20', 'unique:carreras,clave,' . $carrera->id],
            'nombre'      => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $carrera->update($request->only('clave', 'nombre', 'descripcion'));

        return redirect()->route('admin.carreras.index')->with('success', 'Carrera actualizada.');
    }

    public function destroy(Carrera $carrera): RedirectResponse
    {
        if ($carrera->aspirantes()->exists() || $carrera->alumnos()->exists()) {
            return back()->with('error', 'No se puede eliminar una carrera que tiene aspirantes o alumnos.');
        }

        $nombre = $carrera->nombre;
        $carrera->delete();

        return redirect()->route('admin.carreras.index')->with('success', "Carrera {$nombre} eliminada.");
    }

    public function toggle(Carrera $carrera): RedirectResponse
    {
        $carrera->update(['activa' => ! $carrera->activa]);
        $estado = $carrera->activa ? 'activada' : 'desactivada';
        return back()->with('success', "Carrera {$estado}.");
    }
}
