<?php

namespace App\Http\Controllers\Portal\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Illuminate\View\View;

class DocumentoAlumnoController extends Controller
{
    public function index(): View
    {
        $alumno    = auth('alumno')->user();
        $aspirante = $alumno->aspirante;

        $documentos = $aspirante
            ? $aspirante->documentos()->latest()->get()
            : collect();

        $tipos = Documento::tipos();

        return view('portal.alumno.documentos', compact('alumno', 'documentos', 'tipos'));
    }
}
