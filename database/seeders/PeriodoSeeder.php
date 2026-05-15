<?php

namespace Database\Seeders;

use App\Models\Periodo;
use Illuminate\Database\Seeder;

class PeriodoSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = [
            [
                'nombre'       => '2023-A',
                'anio'         => 2023,
                'ciclo'        => 'A',
                'fecha_inicio' => '2023-01-16',
                'fecha_fin'    => '2023-06-30',
                'activo'       => false,
            ],
            [
                'nombre'       => '2023-B',
                'anio'         => 2023,
                'ciclo'        => 'B',
                'fecha_inicio' => '2023-07-17',
                'fecha_fin'    => '2023-12-15',
                'activo'       => false,
            ],
            [
                'nombre'       => '2024-A',
                'anio'         => 2024,
                'ciclo'        => 'A',
                'fecha_inicio' => '2024-01-15',
                'fecha_fin'    => '2024-06-28',
                'activo'       => false,
            ],
            [
                'nombre'       => '2024-B',
                'anio'         => 2024,
                'ciclo'        => 'B',
                'fecha_inicio' => '2024-07-15',
                'fecha_fin'    => '2024-12-13',
                'activo'       => false,
            ],
            [
                'nombre'       => '2025-A',
                'anio'         => 2025,
                'ciclo'        => 'A',
                'fecha_inicio' => '2025-01-13',
                'fecha_fin'    => '2025-06-27',
                'activo'       => true,    // ← Período activo actual
            ],
        ];

        foreach ($periodos as $periodo) {
            Periodo::updateOrCreate(
                ['nombre' => $periodo['nombre']],
                $periodo
            );
        }

        $this->command->info('✅ Períodos creados correctamente.');
    }
}
