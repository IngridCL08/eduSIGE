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
                'nombre'       => '2024-A',
                'anio'         => 2024,
                'ciclo'        => 'A',
                'tipo'         => 'ene_jun',
                'fecha_inicio' => '2024-01-15',
                'fecha_fin'    => '2024-06-28',
                'num_semanas'  => 16,
                'activo'       => false,
                'estado'       => 'cerrado',
            ],
            [
                'nombre'       => '2024-B',
                'anio'         => 2024,
                'ciclo'        => 'B',
                'tipo'         => 'ago_dic',
                'fecha_inicio' => '2024-08-12',
                'fecha_fin'    => '2024-12-20',
                'num_semanas'  => 16,
                'activo'       => false,
                'estado'       => 'cerrado',
            ],
            [
                'nombre'       => '2025-A',
                'anio'         => 2025,
                'ciclo'        => 'A',
                'tipo'         => 'ene_jun',
                'fecha_inicio' => '2025-01-13',
                'fecha_fin'    => '2025-06-27',
                'num_semanas'  => 16,
                'activo'       => false,
                'estado'       => 'cerrado',
            ],
            [
                'nombre'       => '2025-B',
                'anio'         => 2025,
                'ciclo'        => 'B',
                'tipo'         => 'ago_dic',
                'fecha_inicio' => '2025-08-11',
                'fecha_fin'    => '2025-12-19',
                'num_semanas'  => 16,
                'activo'       => false,
                'estado'       => 'cerrado',
            ],
            [
                'nombre'       => '2026-A',
                'anio'         => 2026,
                'ciclo'        => 'A',
                'tipo'         => 'ene_jun',
                'fecha_inicio' => '2026-01-12',
                'fecha_fin'    => '2026-06-26',
                'num_semanas'  => 16,
                'activo'       => true,
                'estado'       => 'en_curso',
            ],
            [
                'nombre'       => '2026-B',
                'anio'         => 2026,
                'ciclo'        => 'B',
                'tipo'         => 'ago_dic',
                'fecha_inicio' => '2026-08-17',
                'fecha_fin'    => '2026-12-18',
                'num_semanas'  => 16,
                'activo'       => false,
                'estado'       => 'planeacion',
            ],
        ];

        foreach ($periodos as $periodo) {
            Periodo::updateOrCreate(
                ['nombre' => $periodo['nombre']],
                $periodo
            );
        }

        $this->command->info('✅ Períodos del TecNM Pinotepa creados correctamente.');
    }
}
