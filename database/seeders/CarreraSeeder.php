<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run(): void
    {
        $carreras = [
            ['clave' => 'ISC',  'nombre' => 'Ingeniería en Sistemas Computacionales', 'activa' => true],
            ['clave' => 'IAG',  'nombre' => 'Ingeniería en Agronomía',                'activa' => true],
            ['clave' => 'IGE',  'nombre' => 'Ingeniería en Gestión Empresarial',      'activa' => true],
            ['clave' => 'CP',   'nombre' => 'Contador Público',                       'activa' => true],
            ['clave' => 'IIN',  'nombre' => 'Ingeniería Industrial',                  'activa' => true],
            ['clave' => 'IAD',  'nombre' => 'Ingeniería en Administración',           'activa' => true],
        ];

        foreach ($carreras as $carrera) {
            Carrera::updateOrCreate(['clave' => $carrera['clave']], $carrera);
        }

        // Desactivar carreras antiguas que ya no pertenecen al plantel
        $clavesActuales = array_column($carreras, 'clave');
        Carrera::whereNotIn('clave', $clavesActuales)->update(['activa' => false]);

        $this->command->info('✅ Carreras del TecNM Pinotepa creadas correctamente.');
    }
}
