<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\Carrera;
use App\Models\Periodo;
use App\Models\Bitacora;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AspiranteController extends Controller
{
    public function __construct(private ReporteService $reporteService) {}

    public function index(Request $request)
    {
        $query = Aspirante::with(['carrera', 'periodo'])
            ->orderByDesc('created_at');

        if ($request->filled('periodo_id')) {
            $query->porPeriodo($request->integer('periodo_id'));
        }
        if ($request->filled('carrera_id')) {
            $query->porCarrera($request->integer('carrera_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        $aspirantes = $query->paginate(20)->withQueryString();
        $carreras   = Carrera::activas()->orderBy('nombre')->get();
        $periodos   = Periodo::orderByDesc('anio')->get();
        $statuses   = Aspirante::statuses();

        return view('escolar.aspirantes.index', compact('aspirantes', 'carreras', 'periodos', 'statuses'));
    }

    public function create()
    {
        $carreras = Carrera::activas()->orderBy('nombre')->get();
        $periodos = Periodo::orderByDesc('anio')->get();
        return view('escolar.aspirantes.create', compact('carreras', 'periodos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'                 => ['required', 'string', 'max:100'],
            'apellido_paterno'       => ['required', 'string', 'max:100'],
            'apellido_materno'       => ['nullable', 'string', 'max:100'],
            'curp'                   => ['nullable', 'string', 'size:18', 'unique:aspirantes,curp'],
            'fecha_nacimiento'       => ['nullable', 'date', 'before:today'],
            'sexo'                   => ['nullable', 'in:M,F,O'],
            'email'                  => ['required', 'email', 'max:191'],
            'telefono'               => ['nullable', 'string', 'max:20'],
            'direccion'              => ['nullable', 'string', 'max:255'],
            'colonia'                => ['nullable', 'string', 'max:150'],
            'municipio'              => ['nullable', 'string', 'max:150'],
            'estado'                 => ['nullable', 'string', 'max:100'],
            'cp'                     => ['nullable', 'string', 'max:10'],
            'bachillerato'           => ['nullable', 'string', 'max:200'],
            'promedio_bachillerato'  => ['nullable', 'numeric', 'min:0', 'max:10'],
            'anio_egreso'            => ['nullable', 'integer', 'min:2000', 'max:' . now()->year],
            'carrera_id'             => ['required', 'exists:carreras,id'],
            'periodo_id'             => ['required', 'exists:periodos,id'],
        ]);

        $anio  = now()->year;
        $count = Aspirante::whereYear('created_at', '=', $anio)->count();
        $data['folio'] = 'ASP-' . $anio . '-' . str_pad((string) ($count + 1), 6, '0', STR_PAD_LEFT);

        // Generar contraseña inicial para el portal del aspirante
        $passwordPlano = Str::random(10);
        $data['password'] = Hash::make($passwordPlano);

        $aspirante = Aspirante::create($data);

        return redirect()
            ->route('escolar.aspirantes.show', $aspirante)
            ->with('success', "Aspirante {$aspirante->folio} registrado correctamente.")
            ->with('password_inicial', $passwordPlano);
    }

    public function show(Aspirante $aspirante)
    {
        $aspirante->load([
            'carrera', 'periodo', 'documentos.verificadoPor',
            'fichasPago', 'alumno.historialAcademico.periodo',
        ]);
        $tiposDocumento = \App\Models\Documento::tipos();
        return view('escolar.aspirantes.show', compact('aspirante', 'tiposDocumento'));
    }

    public function edit(Aspirante $aspirante)
    {
        $carreras = Carrera::activas()->orderBy('nombre')->get();
        $periodos = Periodo::orderByDesc('anio')->get();
        return view('escolar.aspirantes.edit', compact('aspirante', 'carreras', 'periodos'));
    }

    public function update(Request $request, Aspirante $aspirante): RedirectResponse
    {
        $data = $request->validate([
            'nombre'                 => ['required', 'string', 'max:100'],
            'apellido_paterno'       => ['required', 'string', 'max:100'],
            'apellido_materno'       => ['nullable', 'string', 'max:100'],
            'curp'                   => ['nullable', 'string', 'size:18', 'unique:aspirantes,curp,' . $aspirante->id],
            'fecha_nacimiento'       => ['nullable', 'date', 'before:today'],
            'sexo'                   => ['nullable', 'in:M,F,O'],
            'email'                  => ['required', 'email', 'max:191'],
            'telefono'               => ['nullable', 'string', 'max:20'],
            'direccion'              => ['nullable', 'string', 'max:255'],
            'colonia'                => ['nullable', 'string', 'max:150'],
            'municipio'              => ['nullable', 'string', 'max:150'],
            'estado'                 => ['nullable', 'string', 'max:100'],
            'cp'                     => ['nullable', 'string', 'max:10'],
            'bachillerato'           => ['nullable', 'string', 'max:200'],
            'promedio_bachillerato'  => ['nullable', 'numeric', 'min:0', 'max:10'],
            'anio_egreso'            => ['nullable', 'integer', 'min:2000', 'max:' . now()->year],
            'carrera_id'             => ['required', 'exists:carreras,id'],
            'periodo_id'             => ['required', 'exists:periodos,id'],
            'observaciones'          => ['nullable', 'string'],
        ]);

        $aspirante->update($data);

        return redirect()
            ->route('escolar.aspirantes.show', $aspirante)
            ->with('success', 'Datos del aspirante actualizados correctamente.');
    }

    public function destroy(Aspirante $aspirante): RedirectResponse
    {
        if ($aspirante->alumno) {
            return back()->with('error', 'No se puede eliminar un aspirante que ya es alumno.');
        }

        $folio = $aspirante->folio;
        $aspirante->delete();

        return redirect()
            ->route('escolar.aspirantes.index')
            ->with('success', "Aspirante {$folio} eliminado.");
    }

    public function actualizarEstatus(Request $request, Aspirante $aspirante): RedirectResponse
    {
        $request->validate([
            'status'        => ['required', 'in:' . implode(',', array_keys(Aspirante::statuses()))],
            'observaciones' => ['nullable', 'string'],
        ]);

        // "Inscrito" requiere crear el alumno con credenciales
        if ($request->status === Aspirante::STATUS_INSCRITO) {
            if ($aspirante->alumno) {
                return back()->with('error', 'Este aspirante ya tiene un registro de alumno.');
            }
            if ($aspirante->status !== Aspirante::STATUS_ADMITIDO) {
                return back()->with('error', 'Solo se puede inscribir a un aspirante con estatus Admitido.');
            }

            $anio          = now()->year;
            $claveCar      = $aspirante->carrera->clave ?? 'GEN';
            $matricula     = \App\Models\Alumno::generarNumeroControl($anio, $claveCar);
            $passwordPlano = Str::random(10);

            $alumno = \App\Models\Alumno::create([
                'aspirante_id'        => $aspirante->id,
                'matricula'           => $matricula,
                'carrera_id'          => $aspirante->carrera_id,
                'periodo_ingreso_id'  => $aspirante->periodo_id,
                'status'              => \App\Models\Alumno::STATUS_ACTIVO,
                'password'            => Hash::make($passwordPlano),
                'must_change_password' => true,
            ]);

            $aspirante->update([
                'status'        => Aspirante::STATUS_INSCRITO,
                'observaciones' => $request->observaciones,
            ]);

            Bitacora::registrar(
                'inscripcion',
                "Aspirante {$aspirante->folio} inscrito. Matrícula: {$matricula}",
                \App\Models\Alumno::class,
                $alumno->id,
            );

            return redirect()
                ->route('escolar.aspirantes.show', $aspirante)
                ->with('inscripcion_exitosa', [
                    'nombre'    => $aspirante->nombre_completo,
                    'matricula' => $matricula,
                    'password'  => $passwordPlano,
                ]);
        }

        $aspirante->update([
            'status'        => $request->status,
            'observaciones' => $request->observaciones,
        ]);

        return back()->with('success', 'Estatus actualizado correctamente.');
    }

    public function historial(Aspirante $aspirante)
    {
        $registros = Bitacora::with('user')
            ->where('modelo', 'Aspirante')
            ->where('modelo_id', $aspirante->id)
            ->orderByDesc('created_at')
            ->get();

        return view('escolar.aspirantes.historial', compact('aspirante', 'registros'));
    }

    public function exportarExcel(Request $request)
    {
        $filtros = $request->only(['periodo_id', 'carrera_id', 'status']);
        return $this->reporteService->excelAspirantes($filtros);
    }
}
