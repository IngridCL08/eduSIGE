<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->orderBy('name');

        if ($request->filled('rol')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->rol));
        }
        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$termino}%")
                                       ->orWhere('email', 'like', "%{$termino}%"));
        }

        $usuarios = $query->paginate(20)->withQueryString();
        $roles    = Role::all();

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'activo'   => true,
        ]);

        $user->assignRole($request->role);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', "Usuario {$user->name} creado correctamente.");
    }

    public function edit(User $usuario)
    {
        $roles       = Role::all();
        $rolActual   = $usuario->roles->first()?->name;
        return view('admin.usuarios.edit', compact('usuario', 'roles', 'rolActual'));
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'unique:users,email,' . $usuario->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],
        ]);

        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);
        $usuario->syncRoles([$request->role]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', "Usuario {$usuario->name} actualizado.");
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $nombre = $usuario->name;
        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', "Usuario {$nombre} eliminado.");
    }

    public function toggleActivo(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $usuario->update(['activo' => ! $usuario->activo]);
        $accion = $usuario->activo ? 'activado' : 'desactivado';

        return back()->with('success', "Usuario {$usuario->name} {$accion}.");
    }
}
