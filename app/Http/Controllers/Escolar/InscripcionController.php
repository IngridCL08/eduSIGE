<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\Alumno;
use App\Models\Bitacora;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InscripcionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Aspirante::with(['carrera', 'periodo', 'examenAdmision', 'alumno'])
            ->whereIn('status', [Aspirante::STATUS_ADMITIDO, Aspirante::STATUS_INSCRITO])
            ->orderByDesc('created_at');

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }
        if ($request->filled('carrera_id')) {
            $query->where('carrera_id', $request->carrera_id);
        }
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        $aspirantes = $query->paginate(20)->withQueryString();
        $periodos   = \App\Models\Periodo::orderByDesc('anio')->get();
        $carreras   = \App\Models\Carrera::activas()->orderBy('nombre')->get();

        return view('escolar.inscripcion.index', compact('aspirantes', 'periodos', 'carreras'));
    }

    public function inscribir(Request $request, Aspirante $aspirante): RedirectResponse
    {
        if (! in_array($aspirante->status, [Aspirante::STATUS_ADMITIDO])) {
            return back()->with('error', 'Solo se pueden inscribir aspirantes con estatus Admitido.');
        }

        if ($aspirante->alumno) {
            return back()->with('error', 'Este aspirante ya fue inscrito anteriormente.');
        }

        $anio        = now()->year;
        $claveCar    = $aspirante->carrera->clave ?? 'GEN';
        $matricula   = Alumno::generarNumeroControl($anio, $claveCar);
        $passwordPlano = Str::random(10);

        $alumno = Alumno::create([
            'aspirante_id'      => $aspirante->id,
            'matricula'         => $matricula,
            'carrera_id'        => $aspirante->carrera_id,
            'periodo_ingreso_id' => $aspirante->periodo_id,
            'status'            => Alumno::STATUS_ACTIVO,
            'password'          => Hash::make($passwordPlano),
        ]);

        $aspirante->update(['status' => Aspirante::STATUS_INSCRITO]);

        Bitacora::registrar(
            'inscripcion',
            "Aspirante {$aspirante->folio} inscrito. Matrícula: {$matricula}",
            Alumno::class,
            $alumno->id,
        );

        return redirect()
            ->route('escolar.inscripcion.index')
            ->with('inscripcion_exitosa', [
                'nombre'    => $aspirante->nombre_completo,
                'matricula' => $matricula,
                'password'  => $passwordPlano,
            ]);
    }
}
