<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Collector;
use App\Models\CollectionSchedule;
use App\Models\Customer;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::whereHas('user', fn ($q) => $q->where('status', 'active'))->count(),
            'total_collectors' => Collector::count(),
            'active_collectors' => Collector::whereHas('user', fn ($q) => $q->where('status', 'active'))->count(),
            'todays_collections' => CollectionSchedule::whereDate('scheduled_date', today())->count(),
            'completed_collections' => CollectionSchedule::where('status', 'completed')->count(),
            'pending_collections' => CollectionSchedule::whereIn('status', ['scheduled', 'assigned'])->count(),
            'total_bills' => Bill::count(),
            'paid_bills' => Bill::where('status', 'paid')->count(),
            'unpaid_bills' => Bill::whereIn('status', ['unpaid', 'partially_paid'])->count(),
            'overdue_bills' => Bill::where('status', 'overdue')->count(),
            'total_revenue' => Bill::sum('amount_paid'),
            'monthly_revenue' => Bill::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount_paid'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
