<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name'   => 'Administrador del Sistema',
                'email'  => 'admin@edusige.com',
                'password' => Hash::make('Admin2024!'),
                'activo' => true,
                'role'   => 'admin',
            ],
            [
                'name'   => 'Depto. Recursos Financieros',
                'email'  => 'financiero@edusige.com',
                'password' => Hash::make('Financiero2024!'),
                'activo' => true,
                'role'   => 'financiero',
            ],
            [
                'name'   => 'Depto. Servicios Escolares',
                'email'  => 'escolar@edusige.com',
                'password' => Hash::make('Escolar2024!'),
                'activo' => true,
                'role'   => 'escolar',
            ],
        ];

        foreach ($usuarios as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            $user->syncRoles([$role]);
        }

        $this->command->info('✅ Usuarios de prueba creados correctamente.');
    }
}
