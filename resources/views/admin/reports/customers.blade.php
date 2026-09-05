<x-admin-layout title="Customer Report">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold text-gray-900">Customer Report</h1>
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
            <a href="{{ route('admin.reports.export', ['type' => 'customers', 'format' => 'pdf', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100">Export PDF</a>
            <a href="{{ route('admin.reports.export', ['type' => 'customers', 'format' => 'excel', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="px-4 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-lg hover:bg-green-100">Export Excel</a>
        </div>
    </form>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Total Customers" :value="$summary['total']" icon="users" />
        <x-stat-card label="New This Period" :value="$summary['new_in_period']" icon="users" color="green" />
        <x-stat-card label="Active" :value="$summary['active']" icon="users" color="green" />
        <x-stat-card label="Inactive" :value="$summary['inactive']" icon="users" color="amber" />
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Code</th>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Barangay</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Registered</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($rows as $customer)
                <tr>
                    <td class="px-4 py-3">{{ $customer->customer_code }}</td>
                    <td class="px-4 py-3">{{ $customer->user->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $customer->barangay->name }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$customer->user->status" /></td>
                    <td class="px-4 py-3 text-gray-500">{{ $customer->registered_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">No customers registered in this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
