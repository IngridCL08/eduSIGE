<?php

namespace App\Http\Controllers\Portal\Aspirante;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $aspirante = auth('aspirante')->user();
        $aspirante->load([
            'carrera', 'periodo', 'fichaPago',
            'documentos', 'examenAdmision',
        ]);

        return view('portal.aspirante.dashboard', compact('aspirante'));
    }

    public function examen(): View
    {
        $aspirante = auth('aspirante')->user();
        $aspirante->load(['examenesAdmision.aplicadoPor']);

        return view('portal.aspirante.examen', compact('aspirante'));
    }

    public function perfil(): View
    {
        $aspirante = auth('aspirante')->user();
        $aspirante->load(['carrera', 'periodo']);

        return view('portal.aspirante.perfil', compact('aspirante'));
    }
}
