<x-collector-layout title="Dashboard">
    <h1 class="text-xl font-semibold text-gray-900 mb-4">Dashboard</h1>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-card label="Today" :value="$stats['today']" icon="calendar" />
        <x-stat-card label="Pending" :value="$stats['pending']" icon="calendar" color="amber" />
        <x-stat-card label="Completed" :value="$stats['completed']" icon="calendar" color="green" />
        <x-stat-card label="Missed" :value="$stats['missed']" icon="calendar" color="red" />
        <x-stat-card label="Upcoming" :value="$stats['upcoming']" icon="calendar" />
        <x-stat-card label="Total Waste (kg)" :value="number_format($stats['total_waste_kg'], 1)" icon="trash" color="green" />
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Today's Collections</h2>
        @forelse ($todaysSchedules as $schedule)
            <a href="{{ route('collector.schedules.show', $schedule) }}" class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 text-sm hover:bg-gray-50 -mx-2 px-2 rounded">
                <div>
                    <div class="font-medium text-gray-900">{{ $schedule->customer->user->name }}</div>
                    <div class="text-gray-500 text-xs">{{ $schedule->customer->barangay->name }} · {{ $schedule->wasteType->name }}</div>
                </div>
                <x-status-badge :status="$schedule->status" />
            </a>
        @empty
            <p class="text-sm text-gray-500">No collections scheduled for today.</p>
        @endforelse
    </div>
</x-collector-layout>
