<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreScheduleRequest;
use App\Http\Requests\Admin\UpdateScheduleRequest;
use App\Models\Collector;
use App\Models\CollectionSchedule;
use App\Models\Customer;
use App\Models\Service;
use App\Models\WasteType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', CollectionSchedule::class);

        $schedules = CollectionSchedule::query()
            ->with(['customer.user', 'collector.user', 'service', 'wasteType'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->whereHas('customer.user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('scheduled_date', $request->date('date')))
            ->orderBy('scheduled_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $collectors = Collector::with('user')
            ->whereHas('user', fn ($q) => $q->where('status', 'active'))
            ->get();

        return view('admin.schedules.index', compact('schedules', 'collectors'));
    }

    public function create(): View
    {
        $this->authorize('create', CollectionSchedule::class);

        return view('admin.schedules.create', $this->formData());
    }

    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        $this->authorize('create', CollectionSchedule::class);

        $validated = $request->validated();
        $customer = Customer::findOrFail($validated['customer_id']);

        CollectionSchedule::create([
            ...$validated,
            'barangay_id' => $customer->barangay_id,
            'status' => !empty($validated['collector_id']) ? 'assigned' : 'scheduled',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created.');
    }

    public function edit(CollectionSchedule $schedule): View
    {
        $this->authorize('update', $schedule);

        $schedule->load(['customer.user', 'collector.user']);

        return view('admin.schedules.edit', array_merge(['schedule' => $schedule], $this->formData()));
    }

    public function update(UpdateScheduleRequest $request, CollectionSchedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        $validated = $request->validated();
        $customer = Customer::findOrFail($validated['customer_id']);

        $schedule->update([
            ...$validated,
            'barangay_id' => $customer->barangay_id,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated.');
    }

    public function assignCollector(Request $request, CollectionSchedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        $request->validate([
            'collector_id' => ['required', 'exists:collectors,id'],
        ]);

        $schedule->update([
            'collector_id' => $request->collector_id,
            'status' => 'assigned',
        ]);

        return back()->with('success', 'Collector assigned.');
    }

    public function cancel(CollectionSchedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        $schedule->update(['status' => 'cancelled']);

        return back()->with('success', 'Schedule cancelled.');
    }

    private function formData(): array
    {
        return [
            'customers' => Customer::with('user')->orderBy('id')->get(),
            'collectors' => Collector::with('user')->whereHas('user', fn ($q) => $q->where('status', 'active'))->get(),
            'services' => Service::where('status', 'active')->orderBy('name')->get(),
            'wasteTypes' => WasteType::where('status', 'active')->orderBy('name')->get(),
        ];
    }
}
