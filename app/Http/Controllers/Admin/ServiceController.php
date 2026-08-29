<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePricingRuleRequest;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\PricingRule;
use App\Models\Service;
use App\Models\WasteType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $this->authorize('manage-catalog');

        $services = Service::withCount('pricingRules')->orderBy('name')->get();

        return view('admin.services.index', compact('services'));
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $this->authorize('manage-catalog');

        $validated = $request->validated();

        Service::create([
            ...$validated,
            'slug' => $this->uniqueSlug($validated['name']),
            'status' => 'active',
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service added.');
    }

    public function show(Service $service): View
    {
        $this->authorize('manage-catalog');

        $service->load(['pricingRules.wasteType']);
        $wasteTypes = WasteType::where('status', 'active')->orderBy('name')->get();

        return view('admin.services.show', compact('service', 'wasteTypes'));
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $this->authorize('manage-catalog');

        $service->update($request->validated());

        return redirect()->route('admin.services.show', $service)->with('success', 'Service updated.');
    }

    public function toggleStatus(Service $service): RedirectResponse
    {
        $this->authorize('manage-catalog');

        $service->update([
            'status' => $service->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Service status updated.');
    }

    public function storePricingRule(StorePricingRuleRequest $request, Service $service): RedirectResponse
    {
        $this->authorize('manage-catalog');

        PricingRule::create([
            ...$request->validated(),
            'service_id' => $service->id,
            'status' => 'active',
        ]);

        return back()->with('success', 'Pricing rule added.');
    }

    public function togglePricingRuleStatus(Service $service, PricingRule $pricingRule): RedirectResponse
    {
        $this->authorize('manage-catalog');

        $pricingRule->update([
            'status' => $pricingRule->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Pricing rule status updated.');
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 2;

        while (Service::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
