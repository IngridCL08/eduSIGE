<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run(): void
    {
        $carreras = [
            ['clave' => 'ISIA',  'nombre' => 'Ingeniería en Sistemas Computacionales',       'activa' => true],
            ['clave' => 'IIND',  'nombre' => 'Ingeniería Industrial',                         'activa' => true],
            ['clave' => 'IADM',  'nombre' => 'Licenciatura en Administración de Empresas',    'activa' => true],
            ['clave' => 'IECO',  'nombre' => 'Licenciatura en Economía y Finanzas',           'activa' => true],
            ['clave' => 'ICON',  'nombre' => 'Ingeniería en Construcción',                    'activa' => true],
            ['clave' => 'IDER',  'nombre' => 'Licenciatura en Derecho',                       'activa' => false],
        ];

        foreach ($carreras as $carrera) {
            Carrera::updateOrCreate(['clave' => $carrera['clave']], $carrera);
        }

        $this->command->info('✅ Carreras creadas correctamente.');
    }
}
