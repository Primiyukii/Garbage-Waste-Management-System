<x-customer-layout title="Collection Schedule">
    <h1 class="text-xl font-semibold text-gray-900 mb-4">Collection Schedule</h1>

    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Upcoming</h2>
        @forelse ($upcoming as $date => $schedulesOnDate)
            <div class="mb-4 last:mb-0">
                <div class="text-xs font-semibold text-gray-500 uppercase mb-2">
                    {{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}
                </div>
                @foreach ($schedulesOnDate as $schedule)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                        <span>{{ $schedule->wasteType->name }} @if($schedule->collector) — {{ $schedule->collector->user->name }} @endif</span>
                        <x-status-badge :status="$schedule->status" />
                    </div>
                @endforeach
            </div>
        @empty
            <p class="text-sm text-gray-500">No upcoming collections scheduled.</p>
        @endforelse
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">History</h2>
        @forelse ($history as $schedule)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                <span>{{ $schedule->scheduled_date->format('M d, Y') }} — {{ $schedule->wasteType->name }}</span>
                <x-status-badge :status="$schedule->status" />
            </div>
        @empty
            <p class="text-sm text-gray-500">No collection history yet.</p>
        @endforelse
        <div class="mt-4">{{ $history->links() }}</div>
    </div>
</x-customer-layout>
