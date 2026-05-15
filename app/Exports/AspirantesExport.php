<?php

namespace App\Exports;

use App\Models\Aspirante;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AspirantesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(private array $filtros = []) {}

    public function query()
    {
        $query = Aspirante::with(['carrera', 'periodo'])
            ->orderBy('apellido_paterno');

        if (! empty($this->filtros['periodo_id'])) {
            $query->where('periodo_id', $this->filtros['periodo_id']);
        }
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
            'Folio', 'Apellido Paterno', 'Apellido Materno', 'Nombre',
            'CURP', 'Fecha Nacimiento', 'Sexo', 'Email', 'Teléfono',
            'Municipio', 'Estado', 'Bachillerato', 'Promedio Bach.',
            'Carrera', 'Período', 'Estatus',
        ];
    }

    public function map($aspirante): array
    {
        return [
            $aspirante->folio,
            $aspirante->apellido_paterno,
            $aspirante->apellido_materno,
            $aspirante->nombre,
            $aspirante->curp ?? '—',
            $aspirante->fecha_nacimiento?->format('d/m/Y') ?? '—',
            $aspirante->sexo_nombre,
            $aspirante->email,
            $aspirante->telefono ?? '—',
            $aspirante->municipio ?? '—',
            $aspirante->estado ?? '—',
            $aspirante->bachillerato ?? '—',
            $aspirante->promedio_bachillerato ?? '—',
            $aspirante->carrera?->nombre ?? '—',
            $aspirante->periodo?->nombre ?? '—',
            $aspirante->status_nombre,
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
        return 'Aspirantes';
    }
}
