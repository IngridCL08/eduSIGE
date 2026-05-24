<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Carrera;
use App\Models\PlanEstudio;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MateriaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Materia::query();

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', "%{$request->buscar}%")
                  ->orWhere('clave', 'like', "%{$request->buscar}%");
        }
        if ($request->filled('semestre')) {
            $query->where('semestre_sugerido', $request->semestre);
        }

        $materias = $query->orderBy('semestre_sugerido')->orderBy('clave')->paginate(20)->withQueryString();

        return view('escolar.materias.index', compact('materias'));
    }

    public function create(): View
    {
        return view('escolar.materias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'clave'            => ['required', 'string', 'max:20', 'unique:materias,clave'],
            'nombre'           => ['required', 'string', 'max:120'],
            'creditos'         => ['required', 'integer', 'min:1', 'max:20'],
            'horas_teoria'     => ['required', 'integer', 'min:0'],
            'horas_practica'   => ['required', 'integer', 'min:0'],
            'semestre_sugerido'=> ['nullable', 'integer', 'min:1', 'max:8'],
        ]);

        Materia::create($data);

        return redirect()->route('escolar.materias.index')
            ->with('success', "Materia {$data['clave']} — {$data['nombre']} creada correctamente.");
    }

    public function show(Materia $materia): View
    {
        $materia->load('planEstudios.carrera');
        $carreras = Carrera::activas()->get();
        return view('escolar.materias.show', compact('materia', 'carreras'));
    }

    public function edit(Materia $materia): View
    {
        return view('escolar.materias.edit', compact('materia'));
    }

    public function update(Request $request, Materia $materia): RedirectResponse
    {
        $data = $request->validate([
            'clave'            => ['required', 'string', 'max:20', "unique:materias,clave,{$materia->id}"],
            'nombre'           => ['required', 'string', 'max:120'],
            'creditos'         => ['required', 'integer', 'min:1', 'max:20'],
            'horas_teoria'     => ['required', 'integer', 'min:0'],
            'horas_practica'   => ['required', 'integer', 'min:0'],
            'semestre_sugerido'=> ['nullable', 'integer', 'min:1', 'max:8'],
            'activa'           => ['boolean'],
        ]);

        $data['activa'] = $request->boolean('activa');
        $materia->update($data);

        return redirect()->route('escolar.materias.index')
            ->with('success', 'Materia actualizada correctamente.');
    }

    public function destroy(Materia $materia): RedirectResponse
    {
        if ($materia->planEstudios()->exists()) {
            return back()->with('error', 'No se puede eliminar: la materia pertenece a un plan de estudios.');
        }

        $materia->delete();
        return redirect()->route('escolar.materias.index')
            ->with('success', 'Materia eliminada.');
    }

    // ─── Plan de Estudios ─────────────────────────────────────

    public function planIndex(Carrera $carrera): View
    {
        $plan = PlanEstudio::with('materia')
            ->where('carrera_id', $carrera->id)
            ->orderBy('semestre')->orderBy('materia_id')
            ->get()
            ->groupBy('semestre');

        $materiasDisponibles = Materia::activas()
            ->whereNotIn('id', PlanEstudio::where('carrera_id', $carrera->id)->pluck('materia_id'))
            ->orderBy('clave')->get();

        $carreras = Carrera::activas()->orderBy('nombre')->get();

        return view('escolar.materias.plan', compact('carrera', 'plan', 'materiasDisponibles', 'carreras'));
    }

    public function planStore(Request $request, Carrera $carrera): RedirectResponse
    {
        $data = $request->validate([
            'materia_id'  => ['required', 'exists:materias,id'],
            'semestre'    => ['required', 'integer', 'min:1', 'max:9'],
            'obligatoria' => ['boolean'],
        ]);

        PlanEstudio::firstOrCreate(
            ['carrera_id' => $carrera->id, 'materia_id' => $data['materia_id']],
            ['semestre' => $data['semestre'], 'obligatoria' => $data['obligatoria'] ?? true],
        );

        return back()->with('success', 'Materia agregada al plan de estudios.');
    }

    public function planDestroy(PlanEstudio $plan): RedirectResponse
    {
        $plan->delete();
        return back()->with('success', 'Materia removida del plan de estudios.');
    }
}
