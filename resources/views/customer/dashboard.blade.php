<x-customer-layout title="Dashboard">
    <h1 class="text-xl font-semibold text-gray-900 mb-4">Welcome back, {{ auth()->user()->name }}</h1>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-card label="Outstanding Balance" :value="'₱' . number_format($stats['outstanding_balance'], 2)" icon="credit-card" :color="$stats['outstanding_balance'] > 0 ? 'amber' : 'green'" />
        <x-stat-card label="Total Paid" :value="'₱' . number_format($stats['total_paid'], 2)" icon="credit-card" color="green" />
        <x-stat-card label="Total Waste Collected" :value="number_format($stats['total_waste_kg'], 1) . ' kg'" icon="trash" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Next Collection</h2>
            @if ($nextCollection)
                <div class="text-sm space-y-2">
                    <div class="flex justify-between"><span class="text-gray-500">Date</span><span>{{ $nextCollection->scheduled_date->format('M d, Y') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Waste Type</span><span>{{ $nextCollection->wasteType->name }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Collector</span><span>{{ $nextCollection->collector->user->name ?? 'Not yet assigned' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Status</span><x-status-badge :status="$nextCollection->status" /></div>
                </div>
            @else
                <p class="text-sm text-gray-500">No upcoming collection scheduled.</p>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Current Bill</h2>
            @if ($currentBill)
                <div class="text-sm space-y-2">
                    <div class="flex justify-between"><span class="text-gray-500">Bill Number</span><span>{{ $currentBill->bill_number }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Total</span><span>₱{{ number_format($currentBill->total_amount, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Due Date</span><span>{{ $currentBill->due_date->format('M d, Y') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Status</span><x-status-badge :status="$currentBill->status" /></div>
                </div>
                <a href="{{ route('customer.bills.show', $currentBill) }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">View Bill</a>
            @else
                <p class="text-sm text-gray-500">No outstanding bills.</p>
            @endif
        </div>
    </div>

    @if ($lastCollection)
        <div class="bg-white rounded-xl border border-gray-200 p-5 mt-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-2">Last Collection</h2>
            <p class="text-sm text-gray-600">{{ $lastCollection->collection_date->format('M d, Y') }} — {{ $lastCollection->weight_kg }} kg collected</p>
        </div>
    @endif
</x-customer-layout>
