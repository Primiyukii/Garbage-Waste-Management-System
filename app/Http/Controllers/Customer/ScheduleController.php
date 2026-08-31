<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $customer = auth()->user()->customer;

        $upcoming = $customer->collectionSchedules()
            ->whereIn('status', ['scheduled', 'assigned', 'in_progress'])
            ->where('scheduled_date', '>=', today())
            ->with(['wasteType', 'collector.user'])
            ->orderBy('scheduled_date')
            ->get()
            ->groupBy(fn ($schedule) => $schedule->scheduled_date->format('Y-m-d'));

        $history = $customer->collectionSchedules()
            ->whereIn('status', ['completed', 'missed', 'cancelled'])
            ->with(['wasteType', 'collector.user'])
            ->orderByDesc('scheduled_date')
            ->paginate(10);

        return view('customer.schedules.index', compact('upcoming', 'history'));
    }
}
