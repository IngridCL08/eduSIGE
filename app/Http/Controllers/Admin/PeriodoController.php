<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PeriodoController extends Controller
{
    public function index()
    {
        $periodos = Periodo::withCount('aspirantes')->orderByDesc('anio')->orderBy('ciclo')->paginate(20);
        return view('admin.periodos.index', compact('periodos'));
    }

    public function create()
    {
        return view('admin.periodos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre'       => ['required', 'string', 'max:100', 'unique:periodos,nombre'],
            'anio'         => ['required', 'integer', 'min:2000', 'max:2100'],
            'ciclo'        => ['required', 'in:A,B,C'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['required', 'date', 'after:fecha_inicio'],
        ]);

        Periodo::create($request->only('nombre', 'anio', 'ciclo', 'fecha_inicio', 'fecha_fin'));

        return redirect()->route('admin.periodos.index')->with('success', 'Período creado.');
    }

    public function edit(Periodo $periodo)
    {
        return view('admin.periodos.edit', compact('periodo'));
    }

    public function update(Request $request, Periodo $periodo): RedirectResponse
    {
        $request->validate([
            'nombre'       => ['required', 'string', 'max:100', 'unique:periodos,nombre,' . $periodo->id],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['required', 'date', 'after:fecha_inicio'],
        ]);

        $periodo->update($request->only('nombre', 'fecha_inicio', 'fecha_fin'));

        return redirect()->route('admin.periodos.index')->with('success', 'Período actualizado.');
    }

    public function destroy(Periodo $periodo): RedirectResponse
    {
        if ($periodo->aspirantes()->exists()) {
            return back()->with('error', 'No se puede eliminar un período con aspirantes registrados.');
        }

        $nombre = $periodo->nombre;
        $periodo->delete();

        return redirect()->route('admin.periodos.index')->with('success', "Período {$nombre} eliminado.");
    }

    public function activar(Periodo $periodo): RedirectResponse
    {
        // Desactivar todos los demás
        Periodo::where('id', '!=', $periodo->id)->update(['activo' => false]);
        $periodo->update(['activo' => true]);

        return back()->with('success', "Período {$periodo->nombre} marcado como activo.");
    }
}
