<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWasteTypeRequest;
use App\Http\Requests\Admin\UpdateWasteTypeRequest;
use App\Models\WasteType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WasteTypeController extends Controller
{
    public function index(): View
    {
        $this->authorize('manage-catalog');

        $wasteTypes = WasteType::orderBy('name')->get();

        return view('admin.waste-types.index', compact('wasteTypes'));
    }

    public function store(StoreWasteTypeRequest $request): RedirectResponse
    {
        $this->authorize('manage-catalog');

        $validated = $request->validated();

        WasteType::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_hazardous' => $request->boolean('is_hazardous'),
            'status' => 'active',
        ]);

        return back()->with('success', 'Waste type added.');
    }

    public function update(UpdateWasteTypeRequest $request, WasteType $wasteType): RedirectResponse
    {
        $this->authorize('manage-catalog');

        $validated = $request->validated();

        $wasteType->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_hazardous' => $request->boolean('is_hazardous'),
        ]);

        return back()->with('success', 'Waste type updated.');
    }

    public function toggleStatus(WasteType $wasteType): RedirectResponse
    {
        $this->authorize('manage-catalog');

        $wasteType->update([
            'status' => $wasteType->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Waste type status updated.');
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 2;

        while (WasteType::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
