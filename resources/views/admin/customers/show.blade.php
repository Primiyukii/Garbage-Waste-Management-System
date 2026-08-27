<x-admin-layout title="Customer Profile">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ $customer->user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $customer->customer_code }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Edit</a>
            <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3">
            <h3 class="text-sm font-semibold text-gray-700">Account Information</h3>
            <dl class="text-sm space-y-2">
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd>{{ $customer->user->email }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd>{{ $customer->user->phone ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><x-status-badge :status="$customer->user->status" /></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Barangay</dt><dd>{{ $customer->barangay->name }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Address</dt><dd>{{ $customer->house_number }} {{ $customer->street }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Service</dt><dd>{{ $customer->service->name ?? 'None' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Registered</dt><dd>{{ $customer->registered_at->format('M d, Y') }}</dd></div>
            </dl>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Collection Schedules</h3>
                @forelse ($schedules as $schedule)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                        <span>{{ $schedule->scheduled_date->format('M d, Y') }} — {{ $schedule->wasteType->name }}</span>
                        <x-status-badge :status="$schedule->status" />
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No collection schedules yet.</p>
                @endforelse
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Bills</h3>
                @forelse ($bills as $bill)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                        <span>{{ $bill->bill_number }} — ₱{{ number_format($bill->total_amount, 2) }}</span>
                        <x-status-badge :status="$bill->status" />
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No bills yet.</p>
                @endforelse
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Payments</h3>
                @forelse ($payments as $payment)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-sm">
                        <span>{{ $payment->payment_reference }} — ₱{{ number_format($payment->amount, 2) }} ({{ ucfirst($payment->method) }})</span>
                        <x-status-badge :status="$payment->status" />
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No payments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
