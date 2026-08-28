<x-admin-layout title="Locations">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Provinces</h3>

            <form method="POST" action="{{ route('admin.locations.provinces.store') }}" class="flex gap-2 mb-4">
                @csrf
                <input type="text" name="province_name" placeholder="New province name" class="flex-1 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Add</button>
            </form>
            @error('province_name') <p class="text-sm text-red-600 mb-2">{{ $message }}</p> @enderror

            <div class="space-y-1 max-h-96 overflow-y-auto">
                @forelse ($provinces as $province)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                        <span>{{ $province->name }} <span class="text-gray-400">({{ $province->cities_count }} cities)</span></span>
                        <form method="POST" action="{{ route('admin.locations.provinces.destroy', $province) }}" onsubmit="return confirm('Delete this province? This will also delete all its cities and barangays.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No provinces yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Cities / Municipalities</h3>

            <form method="POST" action="{{ route('admin.locations.cities.store') }}" class="space-y-2 mb-4">
                @csrf
                <select name="province_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select province</option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <input type="text" name="city_name" placeholder="New city name" class="flex-1 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Add</button>
                </div>
            </form>
            @error('city_name') <p class="text-sm text-red-600 mb-2">{{ $message }}</p> @enderror

            <div class="space-y-1 max-h-96 overflow-y-auto">
                @forelse ($cities as $city)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                        <span>{{ $city->name }} <span class="text-gray-400">({{ $city->province->name }}, {{ $city->barangays_count }} brgys)</span></span>
                        <form method="POST" action="{{ route('admin.locations.cities.destroy', $city) }}" onsubmit="return confirm('Delete this city? This will also delete all its barangays.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No cities yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Barangays</h3>

            <form method="POST" action="{{ route('admin.locations.barangays.store') }}" class="space-y-2 mb-4">
                @csrf
                <select name="city_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select city</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <input type="text" name="barangay_name" placeholder="New barangay name" class="flex-1 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Add</button>
                </div>
            </form>
            @error('barangay_name') <p class="text-sm text-red-600 mb-2">{{ $message }}</p> @enderror

            <div class="space-y-1 max-h-96 overflow-y-auto">
                @forelse ($barangays as $barangay)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                        <span>{{ $barangay->name }} <span class="text-gray-400">({{ $barangay->city->name }})</span></span>
                        <form method="POST" action="{{ route('admin.locations.barangays.destroy', $barangay) }}" onsubmit="return confirm('Delete this barangay?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No barangays yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-admin-layout>
