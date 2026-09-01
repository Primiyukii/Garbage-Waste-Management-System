<x-customer-layout title="Payment History">
    <h1 class="text-xl font-semibold text-gray-900 mb-4">Payment History</h1>

    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
        @forelse ($payments as $payment)
            <div class="flex items-center justify-between p-4 text-sm">
                <div>
                    <div class="font-medium text-gray-900">{{ $payment->payment_reference }}</div>
                    <div class="text-gray-500 text-xs">{{ $payment->paid_at->format('M d, Y') }} · {{ Str::headline($payment->method) }}</div>
                    @if ($payment->receipt)
                        <div class="text-xs text-gray-400">Receipt {{ $payment->receipt->receipt_number }}</div>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <span>₱{{ number_format($payment->amount, 2) }}</span>
                    <x-status-badge :status="$payment->status" />
                </div>
            </div>
        @empty
            <p class="p-6 text-center text-sm text-gray-500">No payments yet.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>
</x-customer-layout>
