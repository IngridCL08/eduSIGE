<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Periodo;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PeriodoController extends Controller
{
    public function index(): View
    {
        $periodos = Periodo::orderByDesc('anio')->orderByDesc('ciclo')->paginate(15);
        return view('escolar.periodos.index', compact('periodos'));
    }

    public function create(): View
    {
        return view('escolar.periodos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'      => ['required', 'string', 'max:60'],
            'anio'        => ['required', 'integer', 'min:2020', 'max:2040'],
            'ciclo'       => ['required', 'in:A,B'],
            'tipo'        => ['required', 'in:ene_jun,ago_dic'],
            'fecha_inicio'=> ['required', 'date'],
            'fecha_fin'   => ['required', 'date', 'after:fecha_inicio'],
            'num_semanas' => ['required', 'integer', 'min:1', 'max:26'],
        ]);

        $data['estado'] = 'planeacion';
        $data['activo'] = false;

        $periodo = Periodo::create($data);

        Bitacora::registrar('crear_periodo', "Periodo {$periodo->nombre} creado", Periodo::class, $periodo->id);

        return redirect()->route('escolar.periodos.index')
            ->with('success', "Periodo {$periodo->nombre} creado correctamente.");
    }

    public function show(Periodo $periodo): View
    {
        $periodo->loadCount(['aspirantes', 'alumnos', 'reinscripciones']);
        return view('escolar.periodos.show', compact('periodo'));
    }

    public function edit(Periodo $periodo): View
    {
        return view('escolar.periodos.edit', compact('periodo'));
    }

    public function update(Request $request, Periodo $periodo): RedirectResponse
    {
        $data = $request->validate([
            'nombre'      => ['required', 'string', 'max:60'],
            'fecha_inicio'=> ['required', 'date'],
            'fecha_fin'   => ['required', 'date', 'after:fecha_inicio'],
            'num_semanas' => ['required', 'integer', 'min:1', 'max:26'],
            'semana_actual'=> ['nullable', 'integer', 'min:1', 'max:26'],
        ]);

        $periodo->update($data);

        return back()->with('success', 'Período actualizado correctamente.');
    }

    /** Activa el período y lo pone en_curso. Desactiva todos los demás. */
    public function activar(Periodo $periodo): RedirectResponse
    {
        if ($periodo->estado === 'cerrado') {
            return back()->with('error', 'No se puede reactivar un período cerrado.');
        }

        Periodo::where('id', '!=', $periodo->id)->update(['activo' => false]);

        $periodo->update([
            'activo'       => true,
            'estado'       => 'en_curso',
            'semana_actual'=> $periodo->semana_actual ?? 1,
        ]);

        Bitacora::registrar('activar_periodo', "Periodo {$periodo->nombre} activado", Periodo::class, $periodo->id);

        return back()->with('success', "Período {$periodo->nombre} activado como período actual.");
    }

    /** Cierra definitivamente el período. */
    public function cerrar(Request $request, Periodo $periodo): RedirectResponse
    {
        $request->validate([
            'motivo_cierre' => ['required', 'string', 'max:255'],
        ]);

        $periodo->update([
            'estado'        => 'cerrado',
            'activo'        => false,
            'motivo_cierre' => $request->motivo_cierre,
        ]);

        Bitacora::registrar('cerrar_periodo', "Periodo {$periodo->nombre} cerrado. Motivo: {$request->motivo_cierre}", Periodo::class, $periodo->id);

        return back()->with('success', "Período {$periodo->nombre} cerrado correctamente.");
    }

    /** Avanza la semana actual del semestre. */
    public function avanzarSemana(Periodo $periodo): RedirectResponse
    {
        if ($periodo->estado !== 'en_curso') {
            return back()->with('error', 'El período no está en curso.');
        }

        $nueva = min(($periodo->semana_actual ?? 0) + 1, $periodo->num_semanas);
        $periodo->update(['semana_actual' => $nueva]);

        return back()->with('success', "Semana actual actualizada a la semana {$nueva}.");
    }

    /** Abre o cierra el sistema de captura de calificaciones. */
    public function toggleCalificaciones(Request $request, Periodo $periodo): RedirectResponse
    {
        if ($periodo->abierto_calificaciones) {
            $periodo->update([
                'abierto_calificaciones'      => false,
                'fecha_cierre_calificaciones' => now()->toDateString(),
            ]);
            $msg = 'Sistema de calificaciones CERRADO.';
        } else {
            $periodo->update([
                'abierto_calificaciones'       => true,
                'fecha_apertura_calificaciones'=> now()->toDateString(),
                'fecha_cierre_calificaciones'  => null,
            ]);
            $msg = 'Sistema de calificaciones ABIERTO.';
        }

        Bitacora::registrar('toggle_calificaciones', $msg, Periodo::class, $periodo->id);

        return back()->with('success', $msg);
    }
}
