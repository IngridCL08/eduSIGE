<?php

namespace App\Http\Controllers\Financiero;

use App\Http\Controllers\Controller;
use App\Models\Adeudo;
use App\Models\Alumno;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PagoAlumnoController extends Controller
{
    public static array $metodos = [
        'efectivo'      => 'Efectivo',
        'transferencia' => 'Transferencia bancaria',
        'tarjeta'       => 'Tarjeta de débito/crédito',
        'cheque'        => 'Cheque',
        'otro'          => 'Otro',
    ];

    public function index(Request $request): View
    {
        $query = Adeudo::with(['alumno.aspirante', 'alumno.carrera', 'periodo', 'validadoPor'])
            ->orderByRaw("FIELD(status, 'pendiente', 'vencido', 'pagado')")
            ->orderByDesc('created_at');

        if ($request->filled('buscar')) {
            $t = $request->buscar;
            $query->whereHas('alumno', function ($q) use ($t) {
                $q->where('matricula', 'like', "%{$t}%")
                  ->orWhereHas('aspirante', fn ($q2) =>
                      $q2->where('nombre', 'like', "%{$t}%")
                         ->orWhere('apellido_paterno', 'like', "%{$t}%")
                  );
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $adeudos = $query->paginate(25)->withQueryString();

        $tipos   = \App\Http\Controllers\Escolar\AdeudoController::$tipos;
        $metodos = self::$metodos;

        // KPIs rápidos
        $totalPendiente = Adeudo::where('status', 'pendiente')->sum('monto');
        $totalPagado    = Adeudo::where('status', 'pagado')->sum('monto');
        $countPendiente = Adeudo::where('status', 'pendiente')->count();

        return view('financiero.pagos.index', compact(
            'adeudos', 'tipos', 'metodos',
            'totalPendiente', 'totalPagado', 'countPendiente'
        ));
    }

    public function validar(Request $request, Adeudo $adeudo): RedirectResponse
    {
        if ($adeudo->status === 'pagado') {
            return back()->with('error', 'Este adeudo ya fue validado anteriormente.');
        }

        $data = $request->validate([
            'fecha_pago'       => ['required', 'date', 'before_or_equal:today'],
            'metodo_pago'      => ['required', 'in:' . implode(',', array_keys(self::$metodos))],
            'referencia_pago'  => ['nullable', 'string', 'max:100'],
            'comprobante'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'observaciones'    => ['nullable', 'string', 'max:300'],
        ]);

        $comprobantePath = null;
        if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')
                ->store("comprobantes/adeudos/{$adeudo->alumno_id}", 'private');
        }

        $adeudo->update([
            'status'           => 'pagado',
            'fecha_pago'       => $data['fecha_pago'],
            'metodo_pago'      => $data['metodo_pago'],
            'referencia_pago'  => $data['referencia_pago'] ?? null,
            'comprobante_path' => $comprobantePath,
            'descripcion'      => $data['observaciones']
                ? ($adeudo->descripcion ? $adeudo->descripcion . "\n[Financiero] " . $data['observaciones'] : '[Financiero] ' . $data['observaciones'])
                : $adeudo->descripcion,
            'validado_por_id'  => auth()->id(),
        ]);

        Bitacora::registrar(
            'validar_pago_alumno',
            "Pago validado: adeudo #{$adeudo->id} — {$adeudo->concepto} — Alumno: {$adeudo->alumno->matricula}",
            Adeudo::class,
            $adeudo->id,
        );

        return back()->with('success', "Pago de «{$adeudo->concepto}» validado correctamente.");
    }

    public function rechazar(Request $request, Adeudo $adeudo): RedirectResponse
    {
        if ($adeudo->status !== 'pendiente') {
            return back()->with('error', 'Solo se pueden rechazar adeudos en estatus pendiente.');
        }

        $request->validate([
            'motivo_rechazo' => ['required', 'string', 'max:300'],
        ]);

        $adeudo->update([
            'descripcion' => ($adeudo->descripcion ? $adeudo->descripcion . "\n" : '')
                . '[Rechazado por Financiero] ' . $request->motivo_rechazo,
        ]);

        Bitacora::registrar(
            'rechazo_pago_alumno',
            "Pago rechazado: adeudo #{$adeudo->id} — Motivo: {$request->motivo_rechazo}",
            Adeudo::class,
            $adeudo->id,
        );

        return back()->with('info', 'El pago fue marcado con observaciones del rechazo.');
    }
}
