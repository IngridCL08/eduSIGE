<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\ExamenAdmision;
use App\Models\Bitacora;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamenAdmisionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Aspirante::with(['carrera', 'periodo', 'examenAdmision'])
            ->whereIn('status', [
                Aspirante::STATUS_FICHA_PAGADA,
                Aspirante::STATUS_DOCUMENTOS_ENTREGADOS,
                Aspirante::STATUS_EXAMEN_APLICADO,
            ])
            ->orderByDesc('created_at');

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        $aspirantes = $query->paginate(20)->withQueryString();
        $periodos   = \App\Models\Periodo::orderByDesc('anio')->get();

        return view('escolar.examenes.index', compact('aspirantes', 'periodos'));
    }

    public function create(Aspirante $aspirante): View
    {
        return view('escolar.examenes.create', compact('aspirante'));
    }

    public function store(Request $request, Aspirante $aspirante): RedirectResponse
    {
        $data = $request->validate([
            'fecha_examen' => ['required', 'date'],
            'calificacion' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'resultado'    => ['nullable', 'in:aprobado,reprobado,lista_espera'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $data['aspirante_id'] = $aspirante->id;
        $data['aplicado_por'] = auth()->id();

        ExamenAdmision::create($data);

        // Actualizar status del aspirante
        if ($data['resultado']) {
            $nuevoStatus = $data['resultado'] === 'aprobado'
                ? Aspirante::STATUS_EXAMEN_APLICADO
                : Aspirante::STATUS_EXAMEN_APLICADO;

            $aspirante->update(['status' => $nuevoStatus]);
        }

        Bitacora::registrar(
            'examen_registrado',
            "Examen registrado para {$aspirante->folio}. Resultado: " . ($data['resultado'] ?? 'pendiente'),
            Aspirante::class,
            $aspirante->id,
        );

        return redirect()->route('escolar.examenes.index')
            ->with('success', "Examen de {$aspirante->nombre_completo} registrado.");
    }

    public function edit(ExamenAdmision $examen): View
    {
        $examen->load('aspirante');
        return view('escolar.examenes.edit', compact('examen'));
    }

    public function update(Request $request, ExamenAdmision $examen): RedirectResponse
    {
        $data = $request->validate([
            'fecha_examen'  => ['required', 'date'],
            'calificacion'  => ['nullable', 'numeric', 'min:0', 'max:100'],
            'resultado'     => ['nullable', 'in:aprobado,reprobado,lista_espera'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $examen->update($data);

        Bitacora::registrar(
            'examen_actualizado',
            "Examen de {$examen->aspirante->folio} actualizado.",
            ExamenAdmision::class,
            $examen->id,
        );

        return redirect()->route('escolar.examenes.index')
            ->with('success', 'Examen actualizado correctamente.');
    }
}
