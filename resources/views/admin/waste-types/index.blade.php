<x-admin-layout title="Waste Types">
    <div x-data="{ createOpen: false }" class="flex justify-end mb-4">
        <button @click="createOpen = true" type="button" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            Add Waste Type
        </button>

        <div x-show="createOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="createOpen = false" @keydown.escape.window="createOpen = false">
            <div class="bg-white rounded-xl p-6 w-full max-w-md" @click.stop>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Waste Type</h3>
                <form method="POST" action="{{ route('admin.waste-types.store') }}" class="space-y-4">
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
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_hazardous" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Hazardous material
                    </label>
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
                <th class="px-4 py-3 text-left">Description</th>
                <th class="px-4 py-3 text-left">Hazardous</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($wasteTypes as $wasteType)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $wasteType->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $wasteType->description ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $wasteType->is_hazardous ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$wasteType->status" /></td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-3" x-data="{ open: false }">
                            <button @click="open = true" type="button" class="text-gray-600 hover:text-gray-800">Edit</button>
                            <form method="POST" action="{{ route('admin.waste-types.toggle-status', $wasteType) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-amber-600 hover:text-amber-800">
                                    {{ $wasteType->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="open = false">
                                <div class="bg-white rounded-xl p-6 w-full max-w-md" @click.stop>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Waste Type</h3>
                                    <form method="POST" action="{{ route('admin.waste-types.update', $wasteType) }}" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                            <input type="text" name="name" value="{{ $wasteType->name }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                            <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ $wasteType->description }}</textarea>
                                        </div>
                                        <label class="flex items-center gap-2 text-sm text-gray-700">
                                            <input type="checkbox" name="is_hazardous" value="1" @checked($wasteType->is_hazardous) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            Hazardous material
                                        </label>
                                        <div class="flex justify-end gap-3 pt-2">
                                            <button type="button" @click="open = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">No waste types yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
