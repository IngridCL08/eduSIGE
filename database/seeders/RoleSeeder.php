<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Resetear caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── Permisos granulares ──────────────────────────────
        $permisos = [
            // Usuarios
            'users.view', 'users.create', 'users.edit', 'users.delete',
            // Carreras y períodos
            'carreras.manage', 'periodos.manage',
            // Configuración
            'config.manage',
            // Bitácora
            'bitacora.view',
            // Aspirantes (financiero)
            'fichas.view', 'fichas.create', 'fichas.manage',
            'aspirantes.financiero.view',
            'reportes.financiero',
            // Aspirantes (escolar)
            'aspirantes.view', 'aspirantes.create', 'aspirantes.edit', 'aspirantes.delete',
            'documentos.manage',
            // Alumnos
            'alumnos.view', 'alumnos.create', 'alumnos.edit', 'alumnos.delete',
            // Estadísticas
            'estadisticas.view',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        // ─── Rol: Super Administrador ─────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // ─── Rol: Recursos Financieros ────────────────────────
        $financiero = Role::firstOrCreate(['name' => 'financiero', 'guard_name' => 'web']);
        $financiero->syncPermissions([
            'fichas.view', 'fichas.create', 'fichas.manage',
            'aspirantes.financiero.view',
            'reportes.financiero',
        ]);

        // ─── Rol: Control Escolar ──────────────────────────────
        $escolar = Role::firstOrCreate(['name' => 'escolar', 'guard_name' => 'web']);
        $escolar->syncPermissions([
            'aspirantes.view', 'aspirantes.create', 'aspirantes.edit', 'aspirantes.delete',
            'documentos.manage',
            'alumnos.view', 'alumnos.create', 'alumnos.edit', 'alumnos.delete',
            'estadisticas.view',
        ]);

        $this->command->info('✅ Roles y permisos creados correctamente.');
    }
}
