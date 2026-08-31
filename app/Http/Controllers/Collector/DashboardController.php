<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\CollectionSchedule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $collector = auth()->user()->collector;

        $stats = [
            'today' => CollectionSchedule::where('collector_id', $collector->id)->whereDate('scheduled_date', today())->count(),
            'upcoming' => CollectionSchedule::where('collector_id', $collector->id)->where('scheduled_date', '>', today())->whereIn('status', ['scheduled', 'assigned'])->count(),
            'completed' => CollectionSchedule::where('collector_id', $collector->id)->where('status', 'completed')->count(),
            'pending' => CollectionSchedule::where('collector_id', $collector->id)->whereIn('status', ['scheduled', 'assigned'])->count(),
            'missed' => CollectionSchedule::where('collector_id', $collector->id)->where('status', 'missed')->count(),
            'total_waste_kg' => $collector->collectionRecords()->sum('weight_kg'),
        ];

        $todaysSchedules = CollectionSchedule::where('collector_id', $collector->id)
            ->whereDate('scheduled_date', today())
            ->with(['customer.user', 'customer.barangay', 'wasteType'])
            ->get();

        return view('collector.dashboard', compact('stats', 'todaysSchedules'));
    }
}
