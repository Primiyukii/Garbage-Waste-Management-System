<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collector\RecordCollectionRequest;
use App\Models\CollectionSchedule;
use App\Models\WasteType;
use App\Services\CollectionService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(private CollectionService $collectionService)
    {
    }

    public function index(Request $request): View
    {
        $collector = auth()->user()->collector;

        $schedules = CollectionSchedule::query()
            ->where('collector_id', $collector->id)
            ->with(['customer.user', 'customer.barangay', 'service', 'wasteType'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('scheduled_date')
            ->paginate(15)
            ->withQueryString();

        return view('collector.schedules.index', compact('schedules'));
    }

    public function show(CollectionSchedule $schedule): View
    {
        $this->authorize('view', $schedule);

        $schedule->load(['customer.user', 'customer.barangay', 'service', 'wasteType']);
        $wasteTypes = WasteType::where('status', 'active')->orderBy('name')->get();

        return view('collector.schedules.show', compact('schedule', 'wasteTypes'));
    }

    public function start(CollectionSchedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        $this->collectionService->start($schedule);

        return back()->with('success', 'Collection started.');
    }

    public function complete(RecordCollectionRequest $request, CollectionSchedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        $this->collectionService->complete($schedule, $request->validated(), $request->file('photo'));

        return redirect()->route('collector.schedules.index')->with('success', 'Collection completed.');
    }

    public function missed(Request $request, CollectionSchedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        $request->validate(['notes' => ['nullable', 'string']]);

        $this->collectionService->reportMissed($schedule, $request->notes);

        return redirect()->route('collector.schedules.index')->with('success', 'Collection reported as missed.');
    }
}
