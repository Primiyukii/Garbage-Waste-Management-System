<x-admin-layout title="Waste Report">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold text-gray-900">Waste Report</h1>
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
            <a href="{{ route('admin.reports.export', ['type' => 'waste', 'format' => 'pdf', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100">Export PDF</a>
            <a href="{{ route('admin.reports.export', ['type' => 'waste', 'format' => 'excel', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="px-4 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-lg hover:bg-green-100">Export Excel</a>
        </div>
    </form>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <x-stat-card label="Total Waste Collected" :value="number_format($summary['total_kg'], 1) . ' kg'" icon="trash" color="green" />
        <x-stat-card label="Collection Records" :value="$summary['total_records']" icon="calendar" />
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Waste Type</th>
                <th class="px-4 py-3 text-left">Total Kg</th>
                <th class="px-4 py-3 text-left">Collections</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($rows as $row)
                <tr>
                    <td class="px-4 py-3">{{ $row['waste_type'] }}</td>
                    <td class="px-4 py-3">{{ number_format($row['total_kg'], 2) }} kg</td>
                    <td class="px-4 py-3 text-gray-500">{{ $row['collections'] }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-12 text-center text-gray-500">No waste collected in this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
