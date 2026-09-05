<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\CollectionRecord;
use App\Models\CollectionSchedule;
use App\Models\Customer;
use Carbon\Carbon;

class ReportService
{
    public function customers(Carbon $from, Carbon $to): array
    {
        return [
            'summary' => [
                'total' => Customer::count(),
                'new_in_period' => Customer::whereBetween('registered_at', [$from, $to])->count(),
                'active' => Customer::whereHas('user', fn ($q) => $q->where('status', 'active'))->count(),
                'inactive' => Customer::whereHas('user', fn ($q) => $q->where('status', '!=', 'active'))->count(),
            ],
            'rows' => Customer::with(['user', 'barangay'])
                ->whereBetween('registered_at', [$from, $to])
                ->orderBy('registered_at')
                ->get(),
        ];
    }

    public function collections(Carbon $from, Carbon $to): array
    {
        $schedules = CollectionSchedule::with(['customer.user', 'collector.user'])
            ->whereBetween('scheduled_date', [$from, $to])
            ->orderBy('scheduled_date')
            ->get();

        return [
            'summary' => [
                'total' => $schedules->count(),
                'completed' => $schedules->where('status', 'completed')->count(),
                'missed' => $schedules->where('status', 'missed')->count(),
                'cancelled' => $schedules->where('status', 'cancelled')->count(),
            ],
            'rows' => $schedules,
        ];
    }

    public function waste(Carbon $from, Carbon $to): array
    {
        $records = CollectionRecord::with('wasteType')
            ->where('status', 'completed')
            ->whereBetween('collection_date', [$from, $to])
            ->get();

        $byType = $records->groupBy(fn ($r) => $r->wasteType->name)
            ->map(fn ($group, $name) => [
                'waste_type' => $name,
                'total_kg' => $group->sum('weight_kg'),
                'collections' => $group->count(),
            ])
            ->values();

        return [
            'summary' => [
                'total_kg' => $records->sum('weight_kg'),
                'total_records' => $records->count(),
            ],
            'rows' => $byType,
        ];
    }

    public function financial(Carbon $from, Carbon $to): array
    {
        $bills = Bill::with('customer.user')
            ->whereBetween('created_at', [$from, $to])
            ->get();

        return [
            'summary' => [
                'total_billed' => $bills->sum('total_amount'),
                'total_paid' => $bills->sum('amount_paid'),
                'total_unpaid' => $bills->whereIn('status', ['unpaid', 'partially_paid'])->sum(fn ($b) => $b->total_amount - $b->amount_paid),
                'total_overdue' => $bills->where('status', 'overdue')->sum('total_amount'),
            ],
            'rows' => $bills,
        ];
    }
}
