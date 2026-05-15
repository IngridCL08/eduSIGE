<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $query = Bitacora::with('user')->orderByDesc('created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('accion')) {
            $query->where('accion', 'like', '%' . $request->accion . '%');
        }
        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        $registros = $query->paginate(30)->withQueryString();
        $usuarios  = User::orderBy('name')->get(['id', 'name']);

        return view('admin.bitacora.index', compact('registros', 'usuarios'));
    }
}
