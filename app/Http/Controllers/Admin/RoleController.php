<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permisos = Permission::all()->groupBy(fn ($p) => explode('.', $p->name)[0]);
        return view('admin.roles.create', compact('permisos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permisos'    => ['nullable', 'array'],
            'permisos.*'  => ['exists:permissions,id'],
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        if ($request->filled('permisos')) {
            $role->syncPermissions($request->permisos);
        }

        return redirect()->route('admin.roles.index')->with('success', "Rol '{$role->name}' creado.");
    }

    public function edit(Role $role)
    {
        $permisos        = Permission::all()->groupBy(fn ($p) => explode('.', $p->name)[0]);
        $permisosActivos = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permisos', 'permisosActivos'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'permisos'   => ['nullable', 'array'],
            'permisos.*' => ['exists:permissions,id'],
        ]);

        $role->syncPermissions($request->permisos ?? []);

        return redirect()->route('admin.roles.index')->with('success', "Permisos de '{$role->name}' actualizados.");
    }

    public function destroy(Role $role): RedirectResponse
    {
        if (in_array($role->name, ['admin', 'financiero', 'escolar'])) {
            return back()->with('error', 'No se pueden eliminar los roles del sistema.');
        }
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Rol eliminado.');
    }
}
