<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Adeudo;
use App\Models\Alumno;
use App\Models\Periodo;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdeudoController extends Controller
{
    public static array $tipos = [
        'financiero'   => 'Financiero',
        'academico'    => 'Académico',
        'biblioteca'   => 'Biblioteca',
        'credencial'   => 'Credencial escolar',
        'seguro_social'=> 'Seguro social',
        'documentacion'=> 'Documentación',
        'otro'         => 'Otro',
    ];

    public function index(Request $request): View
    {
        $query = Adeudo::with(['alumno.aspirante', 'alumno.carrera', 'periodo'])
            ->orderBy('status')
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }
        if ($request->filled('buscar')) {
            $t = $request->buscar;
            $query->whereHas('alumno.aspirante', fn ($q) =>
                $q->where('nombre', 'like', "%{$t}%")
                  ->orWhere('apellido_paterno', 'like', "%{$t}%")
                  ->orWhere('curp', 'like', "%{$t}%")
            )->orWhereHas('alumno', fn ($q) => $q->where('matricula', 'like', "%{$t}%"));
        }

        $adeudos  = $query->paginate(25)->withQueryString();
        $periodos = Periodo::orderByDesc('anio')->get();
        $tipos    = self::$tipos;

        return view('escolar.adeudos.index', compact('adeudos', 'periodos', 'tipos'));
    }

    public function create(Request $request): View
    {
        $alumnos  = Alumno::with('aspirante')->activos()->orderBy('matricula')->get();
        $periodos = Periodo::orderByDesc('anio')->get();
        $alumnoId = $request->query('alumno_id');
        $tipos    = self::$tipos;

        return view('escolar.adeudos.create', compact('alumnos', 'periodos', 'alumnoId', 'tipos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'alumno_id'         => ['required', 'exists:alumnos,id'],
            'periodo_id'        => ['nullable', 'exists:periodos,id'],
            'tipo'              => ['required', 'in:' . implode(',', array_keys(self::$tipos))],
            'concepto'          => ['required', 'string', 'max:150'],
            'descripcion'       => ['nullable', 'string', 'max:500'],
            'monto'             => ['nullable', 'numeric', 'min:0'],
            'fecha_vencimiento' => ['nullable', 'date'],
        ]);

        $data['status']                = 'pendiente';
        $data['registrado_por_nombre'] = auth()->user()->name;

        $adeudo = Adeudo::create($data);

        Bitacora::registrar('crear_adeudo', "Adeudo '{$data['concepto']}' registrado para alumno ID {$data['alumno_id']}", Adeudo::class, $adeudo->id);

        return redirect()->route('escolar.adeudos.index')
            ->with('success', 'Adeudo registrado correctamente.');
    }

    public function show(Adeudo $adeudo): View
    {
        $adeudo->load(['alumno.aspirante', 'alumno.carrera', 'periodo']);
        $tipos = self::$tipos;
        return view('escolar.adeudos.show', compact('adeudo', 'tipos'));
    }

    public function liquidar(Adeudo $adeudo): RedirectResponse
    {
        if ($adeudo->status === 'pagado') {
            return back()->with('error', 'Este adeudo ya fue liquidado.');
        }

        $adeudo->update([
            'status'     => 'pagado',
            'fecha_pago' => now()->toDateString(),
        ]);

        Bitacora::registrar('liquidar_adeudo', "Adeudo ID {$adeudo->id} liquidado", Adeudo::class, $adeudo->id);

        return back()->with('success', 'Adeudo marcado como liquidado.');
    }

    public function destroy(Adeudo $adeudo): RedirectResponse
    {
        if ($adeudo->status === 'pagado') {
            return back()->with('error', 'No se puede eliminar un adeudo ya liquidado.');
        }

        $adeudo->delete();

        return redirect()->route('escolar.adeudos.index')
            ->with('success', 'Adeudo eliminado.');
    }
}
