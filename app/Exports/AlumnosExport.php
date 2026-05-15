<?php

namespace App\Exports;

use App\Models\Alumno;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AlumnosExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(private array $filtros = []) {}

    public function query()
    {
        $query = Alumno::with(['aspirante', 'carrera', 'periodoIngreso'])
            ->orderBy('matricula');

        if (! empty($this->filtros['carrera_id'])) {
            $query->where('carrera_id', $this->filtros['carrera_id']);
        }
        if (! empty($this->filtros['status'])) {
            $query->where('status', $this->filtros['status']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Matrícula', 'Apellido Paterno', 'Apellido Materno', 'Nombre',
            'CURP', 'Email', 'Teléfono',
            'Carrera', 'Período de Ingreso',
            'Promedio General', 'Créditos', 'Estatus',
        ];
    }

    public function map($alumno): array
    {
        $asp = $alumno->aspirante;
        return [
            $alumno->matricula,
            $asp?->apellido_paterno ?? '—',
            $asp?->apellido_materno ?? '—',
            $asp?->nombre ?? '—',
            $asp?->curp ?? '—',
            $asp?->email ?? '—',
            $asp?->telefono ?? '—',
            $alumno->carrera?->nombre ?? '—',
            $alumno->periodoIngreso?->nombre ?? '—',
            $alumno->promedio_general ?? '—',
            $alumno->creditos_acumulados,
            $alumno->status_nombre,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A5F'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Alumnos';
    }
}
