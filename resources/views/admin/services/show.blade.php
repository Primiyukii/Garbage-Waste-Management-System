<x-admin-layout title="Manage Service">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ $service->name }}</h2>
            <p class="text-sm text-gray-500">{{ ucfirst($service->category) }} · {{ Str::headline($service->frequency) }}</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div x-data="{ editOpen: false }" class="bg-white rounded-xl border border-gray-200 p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Service Details</h3>
                <button @click="editOpen = true" type="button" class="text-xs text-indigo-600 hover:text-indigo-800">Edit</button>
            </div>
            <dl class="text-sm space-y-2">
                <div class="flex justify-between"><dt class="text-gray-500">Base Price</dt><dd>₱{{ number_format($service->base_price, 2) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Included Weight</dt><dd>{{ number_format($service->included_weight_kg, 1) }} kg</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><x-status-badge :status="$service->status" /></dd></div>
            </dl>
            @if ($service->description)
                <p class="text-sm text-gray-600 pt-2 border-t border-gray-100">{{ $service->description }}</p>
            @endif

            <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="editOpen = false" @keydown.escape.window="editOpen = false">
                <div class="bg-white rounded-xl p-6 w-full max-w-lg" @click.stop>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Service</h3>
                    <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $service->name) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $service->description) }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="residential" @selected(old('category', $service->category) === 'residential')>Residential</option>
                                    <option value="commercial" @selected(old('category', $service->category) === 'commercial')>Commercial</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                                <select name="frequency" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="daily" @selected(old('frequency', $service->frequency) === 'daily')>Daily</option>
                                    <option value="weekly" @selected(old('frequency', $service->frequency) === 'weekly')>Weekly</option>
                                    <option value="twice_weekly" @selected(old('frequency', $service->frequency) === 'twice_weekly')>Twice Weekly</option>
                                    <option value="monthly" @selected(old('frequency', $service->frequency) === 'monthly')>Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Base Price (₱)</label>
                                <input type="number" step="0.01" min="0" name="base_price" value="{{ old('base_price', $service->base_price) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Included Weight (kg)</label>
                                <input type="number" step="0.01" min="0" name="included_weight_kg" value="{{ old('included_weight_kg', $service->included_weight_kg) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="editOpen = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-data="{ ruleOpen: false }" class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Pricing Rules</h3>
                <button @click="ruleOpen = true" type="button" class="text-xs text-indigo-600 hover:text-indigo-800">Add Rule</button>
            </div>

            <table class="w-full text-sm">
                <thead class="text-gray-500 text-xs uppercase">
                <tr>
                    <th class="py-2 text-left">Type</th>
                    <th class="py-2 text-left">Waste Type</th>
                    <th class="py-2 text-left">Rate</th>
                    <th class="py-2 text-left">Effective</th>
                    <th class="py-2 text-left">Status</th>
                    <th class="py-2 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($service->pricingRules as $rule)
                    <tr>
                        <td class="py-2">{{ Str::headline($rule->rule_type) }}</td>
                        <td class="py-2 text-gray-500">{{ $rule->wasteType->name ?? 'Any' }}</td>
                        <td class="py-2">
                            @if ($rule->unit === 'percentage')
                                {{ $rule->rate }}%
                            @elseif ($rule->unit === 'per_kg')
                                ₱{{ number_format($rule->rate, 2) }}/kg
                            @else
                                ₱{{ number_format($rule->rate, 2) }}
                            @endif
                        </td>
                        <td class="py-2 text-gray-500">
                            {{ $rule->effective_from->format('M d, Y') }}
                            @if ($rule->effective_to) – {{ $rule->effective_to->format('M d, Y') }} @endif
                        </td>
                        <td class="py-2"><x-status-badge :status="$rule->status" /></td>
                        <td class="py-2 text-right">
                            <form method="POST" action="{{ route('admin.services.pricing-rules.toggle-status', [$service, $rule]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-amber-600 hover:text-amber-800 text-xs">
                                    {{ $rule->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-gray-500">No pricing rules yet — base price applies with no adjustments.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div x-show="ruleOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="ruleOpen = false" @keydown.escape.window="ruleOpen = false">
                <div class="bg-white rounded-xl p-6 w-full max-w-lg" @click.stop>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Pricing Rule</h3>
                    <form method="POST" action="{{ route('admin.services.pricing-rules.store', $service) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Applies To Waste Type (optional)</label>
                            <select name="waste_type_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Any waste type</option>
                                @foreach ($wasteTypes as $wasteType)
                                    <option value="{{ $wasteType->id }}">{{ $wasteType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rule Type</label>
                                <select name="rule_type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="additional_weight">Additional Weight</option>
                                    <option value="surcharge">Surcharge</option>
                                    <option value="discount">Discount</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                <select name="unit" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="per_kg">Per Kg</option>
                                    <option value="flat">Flat Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rate</label>
                            <input type="number" step="0.01" min="0" name="rate" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('rate') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Effective From</label>
                                <input type="date" name="effective_from" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('effective_from') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Effective To (optional)</label>
                                <input type="date" name="effective_to" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('effective_to') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="ruleOpen = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Add Rule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
