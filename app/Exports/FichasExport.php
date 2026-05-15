<?php

namespace App\Exports;

use App\Models\FichaPago;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FichasExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(private array $filtros = []) {}

    public function query()
    {
        $query = FichaPago::with(['aspirante.carrera'])
            ->orderByDesc('created_at');

        if (! empty($this->filtros['status'])) {
            $query->where('status', $this->filtros['status']);
        }
        if (! empty($this->filtros['metodo_pago'])) {
            $query->where('metodo_pago', $this->filtros['metodo_pago']);
        }
        if (! empty($this->filtros['inicio'])) {
            $query->where('fecha_emision', '>=', $this->filtros['inicio']);
        }
        if (! empty($this->filtros['fin'])) {
            $query->where('fecha_emision', '<=', $this->filtros['fin']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Folio Ficha', 'Folio Aspirante', 'Nombre Aspirante', 'Carrera',
            'Monto', 'Concepto', 'Fecha Emisión', 'Fecha Vencimiento',
            'Estatus', 'Fecha Pago', 'Método de Pago', 'Referencia',
        ];
    }

    public function map($ficha): array
    {
        $asp = $ficha->aspirante;
        return [
            $ficha->folio_ficha,
            $asp?->folio ?? '—',
            $asp?->nombre_completo ?? '—',
            $asp?->carrera?->nombre ?? '—',
            '$' . number_format($ficha->monto, 2),
            $ficha->concepto,
            $ficha->fecha_emision?->format('d/m/Y'),
            $ficha->fecha_vencimiento?->format('d/m/Y'),
            $ficha->status_nombre,
            $ficha->fecha_pago?->format('d/m/Y H:i') ?? '—',
            $ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : '—',
            $ficha->referencia_pago ?? '—',
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
        return 'Fichas de Pago';
    }
}
