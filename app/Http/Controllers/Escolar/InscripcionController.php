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
        $query = Aspirante::with(['carrera', 'periodo', 'examenAdmision', 'alumno', 'fichasPago', 'documentos'])
            ->whereIn('status', [
                Aspirante::STATUS_ADMITIDO,
                Aspirante::STATUS_FICHA_PAGADA,
                Aspirante::STATUS_DOCUMENTOS_COMPLETOS,
                Aspirante::STATUS_INSCRITO,
            ])
            ->orderByDesc('created_at');

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }
        if ($request->filled('carrera_id')) {
            $query->where('carrera_id', $request->carrera_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        $aspirantes = $query->paginate(20)->withQueryString();
        $periodos   = \App\Models\Periodo::orderByDesc('anio')->get();
        $carreras   = \App\Models\Carrera::activas()->orderBy('nombre')->get();

        return view('escolar.inscripcion.index', compact('aspirantes', 'periodos', 'carreras'));
    }

    public function inscribir(Aspirante $aspirante): RedirectResponse
    {
        // ─── 1. Validar estado del aspirante ─────────────────
        if ($aspirante->alumno) {
            return back()->with('error', 'Este aspirante ya tiene una matrícula generada.');
        }

        if (! in_array($aspirante->status, [
            Aspirante::STATUS_ADMITIDO,
            Aspirante::STATUS_FICHA_PAGADA,
            Aspirante::STATUS_DOCUMENTOS_COMPLETOS,
        ])) {
            return back()->with('error', 'El aspirante debe estar admitido para generar matrícula.');
        }

        // ─── 2. Verificar pago de ficha ───────────────────────
        $fichaPagada = $aspirante->fichasPago()
            ->where('status', \App\Models\FichaPago::STATUS_PAGADO)
            ->exists();

        if (! $fichaPagada) {
            return back()->with('error', 'El aspirante no tiene ficha de pago confirmada. Verifique con Recursos Financieros.');
        }

        // ─── 3. Verificar examen de admisión ──────────────────
        $examen = $aspirante->examenAdmision;
        if (! $examen || $examen->resultado !== \App\Models\ExamenAdmision::RESULTADO_APROBADO) {
            return back()->with('error', 'El aspirante no tiene examen de admisión aprobado.');
        }

        // ─── 4. Verificar documentación completa ─────────────
        $tiposRequeridos = ['acta_nacimiento', 'certificado_bachillerato', 'curp', 'foto'];
        $docsVerificados = $aspirante->documentos()
            ->whereIn('tipo', $tiposRequeridos)
            ->where('verificado', true)
            ->pluck('tipo')
            ->toArray();

        $faltantes = array_diff($tiposRequeridos, $docsVerificados);
        if (! empty($faltantes)) {
            $faltantesStr = implode(', ', array_map(fn ($t) => ucwords(str_replace('_', ' ', $t)), $faltantes));
            return back()->with('error', "Faltan documentos verificados: {$faltantesStr}.");
        }

        // ─── 5. Generar matrícula y crear alumno ──────────────
        $anio      = $aspirante->periodo?->anio ?? now()->year;
        $claveCar  = $aspirante->carrera->clave ?? 'GEN';
        $matricula = Alumno::generarNumeroControl($anio, $claveCar);
        $passwordPlano = Str::random(10);

        $alumno = Alumno::create([
            'aspirante_id'       => $aspirante->id,
            'matricula'          => $matricula,
            'carrera_id'         => $aspirante->carrera_id,
            'periodo_ingreso_id' => $aspirante->periodo_id,
            'semestre_actual'    => 1,
            'status'             => Alumno::STATUS_ACTIVO,
            'creditos_acumulados'=> 0,
            'password'           => Hash::make($passwordPlano),
            'must_change_password'=> true,
        ]);

        $aspirante->update(['status' => Aspirante::STATUS_INSCRITO]);

        Bitacora::registrar(
            'inscripcion',
            "Aspirante {$aspirante->folio} inscrito. Matrícula: {$matricula}. Validaciones: pago ✓ examen ✓ documentos ✓",
            Alumno::class,
            $alumno->id,
        );

        return redirect()
            ->route('escolar.alumnos.show', $alumno)
            ->with('inscripcion_exitosa', [
                'nombre'    => $aspirante->nombre_completo,
                'matricula' => $matricula,
                'password'  => $passwordPlano,
            ]);
    }
}
