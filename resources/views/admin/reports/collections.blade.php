<x-admin-layout title="Collection Report">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold text-gray-900">Collection Report</h1>
        <a href="{{ route('admin.reports.index') }}" class="text-sm text-gray-500 hover:text-gray-700">All Reports</a>
    </div>

    <form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
            <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded-lg border-gray-300 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
            <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded-lg border-gray-300 text-sm">
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Apply</button>
        <div class="ml-auto flex gap-2">
            <a href="{{ route('admin.reports.export', ['type' => 'collections', 'format' => 'pdf', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100">Export PDF</a>
            <a href="{{ route('admin.reports.export', ['type' => 'collections', 'format' => 'excel', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="px-4 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-lg hover:bg-green-100">Export Excel</a>
        </div>
    </form>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Total" :value="$summary['total']" icon="calendar" />
        <x-stat-card label="Completed" :value="$summary['completed']" icon="calendar" color="green" />
        <x-stat-card label="Missed" :value="$summary['missed']" icon="calendar" color="red" />
        <x-stat-card label="Cancelled" :value="$summary['cancelled']" icon="calendar" color="amber" />
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Customer</th>
                <th class="px-4 py-3 text-left">Collector</th>
                <th class="px-4 py-3 text-left">Status</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($rows as $schedule)
                <tr>
                    <td class="px-4 py-3 text-gray-500">{{ $schedule->scheduled_date->format('M d, Y') }}</td>
                    <td class="px-4 py-3">{{ $schedule->customer->user->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $schedule->collector->user->name ?? 'Unassigned' }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$schedule->status" /></td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">No collections in this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
