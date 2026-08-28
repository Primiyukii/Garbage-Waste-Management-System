<x-admin-layout title="Collector Profile">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            @if ($collector->photo)
                <img src="{{ Storage::url($collector->photo) }}" alt="{{ $collector->user->name }}" class="w-14 h-14 rounded-full object-cover">
            @else
                <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-semibold">
                    {{ substr($collector->user->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $collector->user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $collector->employee_id }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.collectors.edit', $collector) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Edit</a>
            <a href="{{ route('admin.collectors.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Assigned" :value="$performance['assigned']" icon="calendar" />
        <x-stat-card label="Completed" :value="$performance['completed']" icon="calendar" color="green" />
        <x-stat-card label="Missed" :value="$performance['missed']" icon="calendar" color="red" />
        <x-stat-card label="Total Waste (kg)" :value="number_format($performance['total_waste_kg'], 1)" icon="trash" color="green" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3">
            <h3 class="text-sm font-semibold text-gray-700">Account Information</h3>
            <dl class="text-sm space-y-2">
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd>{{ $collector->user->email }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd>{{ $collector->user->phone ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><x-status-badge :status="$collector->user->status" /></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Date Hired</dt><dd>{{ $collector->date_hired->format('M d, Y') }}</dd></div>
            </dl>
            <h3 class="text-sm font-semibold text-gray-700 pt-2">Assigned Areas</h3>
            @forelse ($collector->areas as $area)
                <div class="text-sm text-gray-600">{{ $area->name }}, {{ $area->city->name }}</div>
            @empty
                <p class="text-sm text-gray-500">No areas assigned yet.</p>
            @endforelse
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Collection Records</h3>
            @forelse ($recentRecords as $record)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                    <span>{{ $record->collection_date->format('M d, Y') }} — {{ $record->customer->user->name }} ({{ $record->weight_kg ?? 0 }} kg)</span>
                    <x-status-badge :status="$record->status" />
                </div>
            @empty
                <p class="text-sm text-gray-500">No collection records yet.</p>
            @endforelse
        </div>
    </div>
</x-admin-layout>
