<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\CalendarioReinscripcion;
use App\Models\Carrera;
use App\Models\Periodo;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CalendarioReinscripcionController extends Controller
{
    public function index(Request $request): View
    {
        $periodoId = $request->get('periodo_id', optional(Periodo::enCurso())->id);
        $periodo   = $periodoId ? Periodo::find($periodoId) : null;

        $calendarios = CalendarioReinscripcion::with(['carrera', 'periodo'])
            ->when($periodoId, fn ($q) => $q->where('periodo_id', $periodoId))
            ->orderBy('semestre')
            ->orderBy('fecha_hora_inicio')
            ->get();

        $periodos = Periodo::orderByDesc('anio')->get();
        $carreras = Carrera::activas()->orderBy('nombre')->get();

        return view('escolar.calendario.index', compact('calendarios', 'periodo', 'periodos', 'carreras'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'periodo_id'       => ['required', 'exists:periodos,id'],
            'carrera_id'       => ['required', 'exists:carreras,id'],
            'semestre'         => ['required', 'integer', 'min:1', 'max:9'],
            'turno'            => ['required', 'in:matutino,vespertino,mixto,todos'],
            'fecha_hora_inicio'=> ['required', 'date'],
            'fecha_hora_fin'   => ['required', 'date', 'after:fecha_hora_inicio'],
            'instrucciones'    => ['nullable', 'string', 'max:500'],
        ]);

        $cal = CalendarioReinscripcion::create($data);

        $carrera = Carrera::find($data['carrera_id']);
        Bitacora::registrar('crear_calendario', "Ventana reinscripción semestre {$data['semestre']} carrera {$carrera->nombre}", CalendarioReinscripcion::class, $cal->id);

        return back()->with('success', 'Ventana de reinscripción creada correctamente.');
    }

    public function update(Request $request, CalendarioReinscripcion $calendario): RedirectResponse
    {
        $data = $request->validate([
            'fecha_hora_inicio'=> ['required', 'date'],
            'fecha_hora_fin'   => ['required', 'date', 'after:fecha_hora_inicio'],
            'turno'            => ['required', 'in:matutino,vespertino,mixto,todos'],
            'instrucciones'    => ['nullable', 'string', 'max:500'],
        ]);

        $calendario->update($data);

        return back()->with('success', 'Ventana de reinscripción actualizada.');
    }

    public function toggle(CalendarioReinscripcion $calendario): RedirectResponse
    {
        $calendario->update(['activo' => ! $calendario->activo]);
        $estado = $calendario->activo ? 'habilitada' : 'deshabilitada';

        return back()->with('success', "Ventana de reinscripción {$estado}.");
    }

    public function destroy(CalendarioReinscripcion $calendario): RedirectResponse
    {
        $calendario->delete();
        return back()->with('success', 'Ventana de reinscripción eliminada.');
    }
}
