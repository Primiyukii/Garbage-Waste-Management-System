<x-admin-layout title="Services & Pricing">
    <div x-data="{ createOpen: false }" class="flex justify-end mb-4">
        <button @click="createOpen = true" type="button" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            Add Service
        </button>

        <div x-show="createOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="createOpen = false" @keydown.escape.window="createOpen = false">
            <div class="bg-white rounded-xl p-6 w-full max-w-lg" @click.stop>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Service</h3>
                <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="residential" @selected(old('category') === 'residential')>Residential</option>
                                <option value="commercial" @selected(old('category') === 'commercial')>Commercial</option>
                            </select>
                            @error('category') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                            <select name="frequency" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="daily" @selected(old('frequency') === 'daily')>Daily</option>
                                <option value="weekly" @selected(old('frequency') === 'weekly')>Weekly</option>
                                <option value="twice_weekly" @selected(old('frequency') === 'twice_weekly')>Twice Weekly</option>
                                <option value="monthly" @selected(old('frequency') === 'monthly')>Monthly</option>
                            </select>
                            @error('frequency') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Base Price (₱)</label>
                            <input type="number" step="0.01" min="0" name="base_price" value="{{ old('base_price') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('base_price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Included Weight (kg)</label>
                            <input type="number" step="0.01" min="0" name="included_weight_kg" value="{{ old('included_weight_kg') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('included_weight_kg') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="createOpen = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Category</th>
                <th class="px-4 py-3 text-left">Frequency</th>
                <th class="px-4 py-3 text-left">Base Price</th>
                <th class="px-4 py-3 text-left">Included Weight</th>
                <th class="px-4 py-3 text-left">Pricing Rules</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($services as $service)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $service->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ ucfirst($service->category) }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ Str::headline($service->frequency) }}</td>
                    <td class="px-4 py-3 text-gray-600">₱{{ number_format($service->base_price, 2) }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ number_format($service->included_weight_kg, 1) }} kg</td>
                    <td class="px-4 py-3 text-gray-600">{{ $service->pricing_rules_count }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$service->status" /></td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.services.show', $service) }}" class="text-indigo-600 hover:text-indigo-800">Manage</a>
                            <form method="POST" action="{{ route('admin.services.toggle-status', $service) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-amber-600 hover:text-amber-800">
                                    {{ $service->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-4 py-12 text-center text-gray-500">No services yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
