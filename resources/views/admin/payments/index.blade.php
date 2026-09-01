<x-admin-layout title="Payments">
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <form method="GET" class="flex flex-1 gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reference..." class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <select name="method" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Methods</option>
                    @foreach (['gcash','maya','bank_transfer','cash','gateway'] as $method)
                        <option value="{{ $method }}" @selected(request('method') === $method)>{{ Str::headline($method) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
            </form>
            <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 whitespace-nowrap">
                Record Payment
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Reference</th>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-left">Bill</th>
                    <th class="px-4 py-3 text-left">Amount</th>
                    <th class="px-4 py-3 text-left">Method</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.payments.show', $payment) }}'">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $payment->payment_reference }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $payment->customer->user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $payment->bill->bill_number }}</td>
                        <td class="px-4 py-3 text-gray-600">₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ Str::headline($payment->method) }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$payment->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">No payments yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">{{ $payments->links() }}</div>
    </div>
</x-admin-layout>
