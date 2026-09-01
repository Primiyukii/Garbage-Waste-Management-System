<x-admin-layout title="Record Payment">
    <div
        x-data="{
            billBalances: @json($bills->mapWithKeys(fn ($b) => [$b->id => number_format($b->total_amount - $b->amount_paid, 2)])),
            selectedBill: '{{ old('bill_id', request('bill')) }}'
        }"
        class="max-w-lg bg-white rounded-xl border border-gray-200 p-6"
    >
        <form method="POST" action="{{ route('admin.payments.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bill</label>
                <select name="bill_id" x-model="selectedBill" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select bill</option>
                    @foreach ($bills as $bill)
                        <option value="{{ $bill->id }}">{{ $bill->bill_number }} — {{ $bill->customer->user->name }}</option>
                    @endforeach
                </select>
                @error('bill_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1" x-show="selectedBill" x-cloak>
                    Remaining balance: ₱<span x-text="billBalances[selectedBill]"></span>
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Method</label>
                <select name="method" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="cash">Cash</option>
                    <option value="gcash">GCash</option>
                    <option value="maya">Maya</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="gateway">Online Gateway</option>
                </select>
                @error('method') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID (optional)</label>
                <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Record Payment</button>
            </div>
        </form>
    </div>
</x-admin-layout>
