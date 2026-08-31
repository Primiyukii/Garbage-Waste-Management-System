<x-customer-layout title="Bill Details">
    <a href="{{ route('customer.bills.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Back to My Bills</a>

    <div class="bg-white rounded-xl border border-gray-200 p-6 mt-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $bill->bill_number }}</h2>
                <p class="text-sm text-gray-500">{{ $bill->billing_period_start->format('M d') }} – {{ $bill->billing_period_end->format('M d, Y') }}</p>
            </div>
            <x-status-badge :status="$bill->status" />
        </div>

        <table class="w-full text-sm mb-6">
            <thead class="text-gray-500 text-xs uppercase border-b border-gray-100">
            <tr>
                <th class="py-2 text-left">Description</th>
                <th class="py-2 text-right">Qty</th>
                <th class="py-2 text-right">Unit Price</th>
                <th class="py-2 text-right">Amount</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach ($bill->items as $item)
                <tr>
                    <td class="py-2">{{ $item->description }}</td>
                    <td class="py-2 text-right">{{ $item->quantity }}</td>
                    <td class="py-2 text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                    <td class="py-2 text-right">₱{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="border-t border-gray-200 pt-4 space-y-2 text-sm max-w-xs ml-auto">
            <div class="flex justify-between"><span class="text-gray-500">Total Amount</span><span class="font-semibold">₱{{ number_format($bill->total_amount, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Amount Paid</span><span>₱{{ number_format($bill->amount_paid, 2) }}</span></div>
            <div class="flex justify-between font-semibold"><span>Balance</span><span>₱{{ number_format($bill->total_amount - $bill->amount_paid, 2) }}</span></div>
        </div>

        @if ($bill->status !== 'paid' && $bill->status !== 'cancelled')
            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-sm text-gray-500">Online payment will be available in a future update.</p>
            </div>
        @endif
    </div>
</x-customer-layout>
