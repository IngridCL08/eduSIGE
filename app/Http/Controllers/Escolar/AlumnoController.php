<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Aspirante;
use App\Models\Carrera;
use App\Models\Periodo;
use App\Models\HistorialAcademico;
use App\Models\Bitacora;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AlumnoController extends Controller
{
    public function __construct(private ReporteService $reporteService) {}

    public function index(Request $request)
    {
        $query = Alumno::with(['aspirante', 'carrera', 'periodoIngreso'])->orderBy('matricula');

        if ($request->filled('carrera_id')) {
            $query->where('carrera_id', $request->carrera_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        $alumnos  = $query->paginate(20)->withQueryString();
        $carreras = Carrera::activas()->orderBy('nombre')->get();

        return view('escolar.alumnos.index', compact('alumnos', 'carreras'));
    }

    public function create()
    {
        $aspirantes = Aspirante::where('status', 'admitido')
            ->doesntHave('alumno')
            ->with('carrera')
            ->orderBy('apellido_paterno')
            ->get();

        $carreras = Carrera::activas()->get();
        $periodos = Periodo::orderByDesc('anio')->get();

        return view('escolar.alumnos.create', compact('aspirantes', 'carreras', 'periodos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'aspirante_id'       => ['required', 'exists:aspirantes,id', 'unique:alumnos,aspirante_id'],
            'carrera_id'         => ['required', 'exists:carreras,id'],
            'periodo_ingreso_id' => ['required', 'exists:periodos,id'],
            'status'             => ['sometimes', 'in:' . implode(',', array_keys(Alumno::statuses()))],
        ]);

        $aspirante     = Aspirante::findOrFail($request->aspirante_id);
        $anio          = now()->year;
        $claveCar      = $aspirante->carrera->clave ?? 'GEN';
        $matricula     = Alumno::generarNumeroControl($anio, $claveCar);
        $passwordPlano = Str::random(10);

        $alumno = Alumno::create([
            'aspirante_id'        => $aspirante->id,
            'matricula'           => $matricula,
            'carrera_id'          => $request->carrera_id ?? $aspirante->carrera_id,
            'periodo_ingreso_id'  => $request->periodo_ingreso_id,
            'status'              => $request->status ?? Alumno::STATUS_ACTIVO,
            'password'            => Hash::make($passwordPlano),
            'must_change_password' => true,
        ]);

        $aspirante->update(['status' => Aspirante::STATUS_INSCRITO]);

        Bitacora::registrar(
            'inscripcion',
            "Aspirante {$aspirante->folio} inscrito como alumno. Matrícula: {$matricula}",
            Alumno::class,
            $alumno->id,
        );

        return redirect()->route('escolar.alumnos.show', $alumno)
            ->with('inscripcion_exitosa', [
                'nombre'    => $aspirante->nombre_completo,
                'matricula' => $matricula,
                'password'  => $passwordPlano,
            ]);
    }

    public function show(Alumno $alumno)
    {
        $alumno->load(['aspirante.documentos', 'carrera', 'periodoIngreso', 'historial.periodo']);

        return view('escolar.alumnos.show', compact('alumno'));
    }

    public function edit(Alumno $alumno)
    {
        $carreras = Carrera::activas()->get();
        $periodos = Periodo::orderByDesc('anio')->get();

        return view('escolar.alumnos.edit', compact('alumno', 'carreras', 'periodos'));
    }

    public function update(Request $request, Alumno $alumno): RedirectResponse
    {
        $request->validate([
            'carrera_id'          => ['sometimes', 'exists:carreras,id'],
            'periodo_ingreso_id'  => ['sometimes', 'exists:periodos,id'],
            'status'              => ['sometimes', 'in:' . implode(',', array_keys(Alumno::statuses()))],
            'promedio_general'    => ['nullable', 'numeric', 'min:0', 'max:10'],
            'creditos_acumulados' => ['nullable', 'integer', 'min:0'],
        ]);

        $alumno->update($request->only([
            'carrera_id', 'periodo_ingreso_id', 'status', 'promedio_general', 'creditos_acumulados',
        ]));

        return back()->with('success', 'Datos del alumno actualizados correctamente.');
    }

    public function destroy(Alumno $alumno): RedirectResponse
    {
        $matricula = $alumno->matricula;
        $alumno->delete();

        return redirect()->route('escolar.alumnos.index')
            ->with('success', "Alumno {$matricula} eliminado del sistema.");
    }

    public function actualizarEstatus(Request $request, Alumno $alumno): RedirectResponse
    {
        $request->validate([
            'status'        => ['required', 'in:' . implode(',', array_keys(Alumno::statuses()))],
            'observaciones' => ['nullable', 'string'],
        ]);

        $alumno->update([
            'status'        => $request->status,
            'observaciones' => $request->observaciones,
        ]);

        return back()->with('success', 'Estatus del alumno actualizado.');
    }

    public function historialAcademico(Alumno $alumno)
    {
        $historial = $alumno->historial()->with('periodo')->orderByDesc('created_at')->get();
        $periodos  = Periodo::orderByDesc('anio')->get();

        return view('escolar.alumnos.historial', compact('alumno', 'historial', 'periodos'));
    }

    public function agregarMateriaHistorial(Request $request, Alumno $alumno): RedirectResponse
    {
        $validated = $request->validate([
            'materia'       => ['required', 'string', 'max:200'],
            'clave_materia' => ['nullable', 'string', 'max:20'],
            'creditos'      => ['required', 'integer', 'min:0', 'max:20'],
            'periodo_id'    => ['required', 'exists:periodos,id'],
            'calificacion'  => ['nullable', 'numeric', 'min:0', 'max:10'],
            'status'        => ['required', 'in:cursando,acreditada,reprobada,baja'],
        ]);

        HistorialAcademico::create([...$validated, 'alumno_id' => $alumno->id]);

        $alumno->recalcularPromedio();

        return back()->with('success', 'Materia agregada al historial académico.');
    }

    public function exportarExcel(Request $request)
    {
        return $this->reporteService->excelAlumnos($request->only(['carrera_id', 'status']));
    }
}
