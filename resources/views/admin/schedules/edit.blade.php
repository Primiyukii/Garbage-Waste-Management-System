<x-admin-layout title="Edit Schedule">
    <div class="max-w-2xl bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <select name="customer_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id', $schedule->customer_id) == $customer->id)>
                            {{ $customer->user->name }} ({{ $customer->customer_code }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Collector</label>
                <select name="collector_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Unassigned</option>
                    @foreach ($collectors as $collector)
                        <option value="{{ $collector->id }}" @selected(old('collector_id', $schedule->collector_id) == $collector->id)>
                            {{ $collector->user->name }} ({{ $collector->employee_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <select name="service_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id', $schedule->service_id) == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waste Type</label>
                    <select name="waste_type_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($wasteTypes as $wasteType)
                            <option value="{{ $wasteType->id }}" @selected(old('waste_type_id', $schedule->waste_type_id) == $wasteType->id)>{{ $wasteType->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="scheduled_date" value="{{ old('scheduled_date', $schedule->scheduled_date->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('scheduled_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                    <input type="time" name="scheduled_time" value="{{ old('scheduled_time', $schedule->scheduled_time) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                    <select name="frequency" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="daily" @selected(old('frequency', $schedule->frequency) === 'daily')>Daily</option>
                        <option value="weekly" @selected(old('frequency', $schedule->frequency) === 'weekly')>Weekly</option>
                        <option value="twice_weekly" @selected(old('frequency', $schedule->frequency) === 'twice_weekly')>Twice Weekly</option>
                        <option value="monthly" @selected(old('frequency', $schedule->frequency) === 'monthly')>Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (['scheduled','assigned','in_progress','completed','missed','cancelled'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $schedule->status) === $status)>{{ Str::headline($status) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $schedule->notes) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-admin-layout>
