<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $customer = auth()->user()->customer;

        $nextCollection = $customer->collectionSchedules()
            ->whereIn('status', ['scheduled', 'assigned', 'in_progress'])
            ->where('scheduled_date', '>=', today())
            ->orderBy('scheduled_date')
            ->with(['wasteType', 'collector.user'])
            ->first();

        $lastCollection = $customer->collectionRecords()
            ->where('status', 'completed')
            ->latest('collection_date')
            ->first();

        $currentBill = $customer->bills()
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->latest('due_date')
            ->first();

        $stats = [
            'outstanding_balance' => $customer->bills()->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
                ->get()
                ->sum(fn ($bill) => $bill->total_amount - $bill->amount_paid),
            'total_paid' => $customer->payments()->where('status', 'successful')->sum('amount'),
            'total_waste_kg' => $customer->collectionRecords()->where('status', 'completed')->sum('weight_kg'),
        ];

        return view('customer.dashboard', compact('customer', 'nextCollection', 'lastCollection', 'currentBill', 'stats'));
    }
}
