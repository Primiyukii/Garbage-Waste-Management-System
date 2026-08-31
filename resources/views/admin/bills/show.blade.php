<x-admin-layout title="Bill Details">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ $bill->bill_number }}</h2>
            <p class="text-sm text-gray-500">{{ $bill->customer->user->name }} · {{ $bill->billing_period_start->format('M d') }} – {{ $bill->billing_period_end->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('admin.bills.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Back</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <x-status-badge :status="$bill->status" />
            <span class="text-sm text-gray-500">Due {{ $bill->due_date->format('M d, Y') }}</span>
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

        <div class="border-t border-gray-200 pt-4 space-y-2 text-sm max-w-xs ml-auto mb-6">
            <div class="flex justify-between"><span class="text-gray-500">Total Amount</span><span class="font-semibold">₱{{ number_format($bill->total_amount, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Amount Paid</span><span>₱{{ number_format($bill->amount_paid, 2) }}</span></div>
            <div class="flex justify-between font-semibold"><span>Balance</span><span>₱{{ number_format($bill->total_amount - $bill->amount_paid, 2) }}</span></div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Payments</h3>
            @forelse ($bill->payments as $payment)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                    <span>{{ $payment->payment_reference }} — ₱{{ number_format($payment->amount, 2) }} ({{ ucfirst($payment->method) }})</span>
                    <x-status-badge :status="$payment->status" />
                </div>
            @empty
                <p class="text-sm text-gray-500">No payments recorded yet.</p>
            @endforelse
        </div>
    </div>
</x-admin-layout>
