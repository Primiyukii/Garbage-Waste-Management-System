<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialExport implements FromCollection, WithHeadings, WithMapping
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
        return ['Bill Number', 'Customer', 'Total', 'Paid', 'Status', 'Due Date'];
    }

    public function map($bill): array
    {
        return [
            $bill->bill_number,
            $bill->customer->user->name,
            number_format($bill->total_amount, 2),
            number_format($bill->amount_paid, 2),
            ucfirst($bill->status),
            $bill->due_date->format('Y-m-d'),
        ];
    }
}
