<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
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
        return ['Customer Code', 'Name', 'Email', 'Barangay', 'Status', 'Registered'];
    }

    public function map($customer): array
    {
        return [
            $customer->customer_code,
            $customer->user->name,
            $customer->user->email,
            $customer->barangay->name,
            ucfirst($customer->user->status),
            $customer->registered_at->format('Y-m-d'),
        ];
    }
}
