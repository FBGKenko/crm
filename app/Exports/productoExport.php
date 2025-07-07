<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class productoExport implements FromArray, WithHeadings, WithColumnFormatting, WithColumnWidths
{
    protected $datos;
    protected $headings;

    public function __construct(array $datos, array $headings)
    {
        $this->datos = $datos;
        $this->headings = $headings;
    }

    public function array(): array
    {
        return $this->datos;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function columnFormats(): array
    {
        $formats = [];

        // Formato desde la columna D (columna 4) en adelante
        foreach (range(3, count($this->headings) - 1) as $index) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
            $formats[$columnLetter] = '#,##0.00 [$MXN]';

        }

        return $formats;
    }

    public function columnWidths(): array
    {
        $widths = [];

        // Ajustar ancho para las 3 primeras columnas
        // $widths['A'] = 5; // producto_id
        // $widths['B'] = 30; // producto
        // $widths['C'] = 25; // variante

        // Ajustar ancho para precios (desde la columna D en adelante)
        foreach (range(3, count($this->headings) - 1) as $index) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
            $widths[$columnLetter] = 15; // o lo que necesites
        }

        return $widths;
    }

}
