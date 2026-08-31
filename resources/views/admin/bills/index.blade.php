<x-admin-layout title="Billing">
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <form method="GET" class="flex flex-1 gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search bill number..." class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    @foreach (['unpaid','partially_paid','paid','overdue','cancelled'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ Str::headline($status) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
            </form>
            <a href="{{ route('admin.bills.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 whitespace-nowrap">
                Generate Bill
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Bill Number</th>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-left">Period</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Due Date</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($bills as $bill)
                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.bills.show', $bill) }}'">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $bill->bill_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $bill->customer->user->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $bill->billing_period_start->format('M d') }} – {{ $bill->billing_period_end->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-600">₱{{ number_format($bill->total_amount, 2) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $bill->due_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$bill->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">No bills yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">{{ $bills->links() }}</div>
    </div>
</x-admin-layout>
