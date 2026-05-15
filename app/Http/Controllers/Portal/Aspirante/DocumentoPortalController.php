<?php

namespace App\Http\Controllers\Portal\Aspirante;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentoPortalController extends Controller
{
    public function index(): View
    {
        $aspirante  = auth('aspirante')->user();
        $documentos = $aspirante->documentos()->latest()->get();
        $tipos      = Documento::tipos();

        return view('portal.aspirante.documentos', compact('aspirante', 'documentos', 'tipos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $aspirante = auth('aspirante')->user();

        if ($aspirante->tieneFichaPagada() === false) {
            return back()->with('error', 'Debes tener la ficha de pago cubierta antes de subir documentos.');
        }

        $request->validate([
            'tipo'    => ['required', 'in:' . implode(',', array_keys(Documento::tipos()))],
            'archivo' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:8192'],
        ], [
            'archivo.required' => 'Selecciona un archivo.',
            'archivo.mimes'    => 'Solo JPG, PNG o PDF.',
            'archivo.max'      => 'El archivo no debe superar 8 MB.',
        ]);

        // Eliminar documento previo del mismo tipo si existe
        $aspirante->documentos()->where('tipo', $request->tipo)->delete();

        $path = $request->file('archivo')->store(
            "documentos/{$aspirante->folio}",
            'public'
        );

        Documento::create([
            'aspirante_id'   => $aspirante->id,
            'tipo'           => $request->tipo,
            'nombre_archivo' => $request->file('archivo')->getClientOriginalName(),
            'ruta_archivo'   => $path,
            'verificado'     => false,
        ]);

        return back()->with('success', 'Documento subido correctamente. Será verificado por Control Escolar.');
    }
}
