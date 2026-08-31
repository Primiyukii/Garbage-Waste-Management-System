<x-collector-layout title="My Collections">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold text-gray-900">My Collections</h1>
        <form method="GET">
            <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                @foreach (['scheduled','assigned','in_progress','completed','missed','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ Str::headline($status) }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
        @forelse ($schedules as $schedule)
            <a href="{{ route('collector.schedules.show', $schedule) }}" class="flex items-center justify-between p-4 text-sm hover:bg-gray-50">
                <div>
                    <div class="font-medium text-gray-900">{{ $schedule->customer->user->name }}</div>
                    <div class="text-gray-500 text-xs">{{ $schedule->scheduled_date->format('M d, Y') }} · {{ $schedule->customer->barangay->name }}</div>
                </div>
                <x-status-badge :status="$schedule->status" />
            </a>
        @empty
            <p class="p-6 text-center text-sm text-gray-500">No collections found.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $schedules->links() }}</div>
</x-collector-layout>
