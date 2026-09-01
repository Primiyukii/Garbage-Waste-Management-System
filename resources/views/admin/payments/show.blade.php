<x-admin-layout title="Payment Details">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">{{ $payment->payment_reference }}</h2>
        <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Back</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 max-w-lg">
        <div class="flex items-center justify-between mb-4">
            <x-status-badge :status="$payment->status" />
            <span class="text-sm text-gray-500">{{ $payment->paid_at->format('M d, Y g:i A') }}</span>
        </div>

        <dl class="text-sm space-y-2 mb-6">
            <div class="flex justify-between"><dt class="text-gray-500">Customer</dt><dd>{{ $payment->customer->user->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Bill</dt><dd><a href="{{ route('admin.bills.show', $payment->bill) }}" class="text-indigo-600 hover:text-indigo-800">{{ $payment->bill->bill_number }}</a></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Amount</dt><dd class="font-semibold">₱{{ number_format($payment->amount, 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Method</dt><dd>{{ Str::headline($payment->method) }}</dd></div>
            @if ($payment->transaction_id)
                <div class="flex justify-between"><dt class="text-gray-500">Transaction ID</dt><dd>{{ $payment->transaction_id }}</dd></div>
            @endif
        </dl>

        @if ($payment->receipt)
            <div class="border-t border-gray-100 pt-4 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Official Receipt</p>
                <p class="text-lg font-semibold text-gray-900">{{ $payment->receipt->receipt_number }}</p>
                <p class="text-xs text-gray-500">Generated {{ $payment->receipt->generated_at->format('M d, Y g:i A') }}</p>
            </div>
        @endif
    </div>
</x-admin-layout>
