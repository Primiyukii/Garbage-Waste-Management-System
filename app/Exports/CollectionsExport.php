<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Str;
class CollectionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $rows)
    {
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return ['Date', 'Customer', 'Collector', 'Status'];
    }

    public function map($schedule): array
    {
        return [
            $schedule->scheduled_date->format('Y-m-d'),
            $schedule->customer->user->name,
            $schedule->collector->user->name ?? 'Unassigned',
            Str::headline($schedule->status),
        ];
    }
}
