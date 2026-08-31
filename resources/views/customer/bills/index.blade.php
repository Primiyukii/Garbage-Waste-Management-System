<x-customer-layout title="My Bills">
    <h1 class="text-xl font-semibold text-gray-900 mb-4">My Bills</h1>

    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
        @forelse ($bills as $bill)
            <a href="{{ route('customer.bills.show', $bill) }}" class="flex items-center justify-between p-4 text-sm hover:bg-gray-50">
                <div>
                    <div class="font-medium text-gray-900">{{ $bill->bill_number }}</div>
                    <div class="text-gray-500 text-xs">Due {{ $bill->due_date->format('M d, Y') }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <span>₱{{ number_format($bill->total_amount, 2) }}</span>
                    <x-status-badge :status="$bill->status" />
                </div>
            </a>
        @empty
            <p class="p-6 text-center text-sm text-gray-500">No bills yet.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $bills->links() }}</div>
</x-customer-layout>
