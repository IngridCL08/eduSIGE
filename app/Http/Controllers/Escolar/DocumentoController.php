<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Documento::with(['aspirante', 'verificadoPor'])->latest();

        if ($request->filled('aspirante_id')) {
            $query->where('aspirante_id', $request->aspirante_id);
        }

        if ($request->filled('verificado')) {
            $query->where('verificado', (bool) $request->verificado);
        }

        $documentos = $query->paginate(20)->withQueryString();
        $aspirantes = Aspirante::orderBy('apellido_paterno')->get(['id','folio','nombre','apellido_paterno','apellido_materno']);

        return view('escolar.documentos.index', compact('documentos', 'aspirantes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'aspirante_id' => ['required', 'exists:aspirantes,id'],
            'tipo'         => ['required', 'in:' . implode(',', array_keys(Documento::tipos()))],
            'archivo'      => ['required', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $aspirante = Aspirante::findOrFail($request->aspirante_id);
        $archivo   = $request->file('archivo');
        $ruta      = $archivo->store("documentos/{$aspirante->id}", 'public');

        Documento::create([
            'aspirante_id'   => $aspirante->id,
            'tipo'           => $request->tipo,
            'nombre_archivo' => $archivo->getClientOriginalName(),
            'ruta_archivo'   => $ruta,
        ]);

        if ($aspirante->tieneDocumentosCompletos() && $aspirante->status === 'ficha_pagada') {
            $aspirante->update(['status' => 'documentos_entregados']);
        }

        return back()->with('success', 'Documento cargado correctamente.');
    }

    public function verificar(Documento $documento): RedirectResponse
    {
        $nuevoEstado = ! $documento->verificado;

        $documento->update([
            'verificado'     => $nuevoEstado,
            'verificado_por' => $nuevoEstado ? auth()->id() : null,
            'verificado_at'  => $nuevoEstado ? now() : null,
        ]);

        return back()->with('success', 'Estado del documento actualizado.');
    }

    public function destroy(Documento $documento): RedirectResponse
    {
        Storage::disk('public')->delete($documento->ruta_archivo);
        $documento->delete();

        return back()->with('success', 'Documento eliminado.');
    }
}
