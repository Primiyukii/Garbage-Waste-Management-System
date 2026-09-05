<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WasteExport implements FromCollection, WithHeadings
{
    public function __construct(private Collection $rows)
    {
    }

    public function collection(): Collection
    {
        return $this->rows->map(fn ($row) => [
            $row['waste_type'],
            number_format($row['total_kg'], 2),
            $row['collections'],
        ]);
    }

    public function headings(): array
    {
        return ['Waste Type', 'Total Kg', 'Collections'];
    }
}
