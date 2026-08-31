<x-admin-layout title="Add Schedule">
    <div class="max-w-2xl bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.schedules.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <select name="customer_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                            {{ $customer->user->name }} ({{ $customer->customer_code }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Collector (optional — can assign later)</label>
                <select name="collector_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Unassigned</option>
                    @foreach ($collectors as $collector)
                        <option value="{{ $collector->id }}" @selected(old('collector_id') == $collector->id)>
                            {{ $collector->user->name }} ({{ $collector->employee_id }})
                        </option>
                    @endforeach
                </select>
                @error('collector_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <select name="service_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select service</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waste Type</label>
                    <select name="waste_type_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select waste type</option>
                        @foreach ($wasteTypes as $wasteType)
                            <option value="{{ $wasteType->id }}" @selected(old('waste_type_id') == $wasteType->id)>{{ $wasteType->name }}</option>
                        @endforeach
                    </select>
                    @error('waste_type_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="scheduled_date" value="{{ old('scheduled_date') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('scheduled_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time (optional)</label>
                    <input type="time" name="scheduled_time" value="{{ old('scheduled_time') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('scheduled_time') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Create Schedule</button>
            </div>
        </form>
    </div>
</x-admin-layout>
