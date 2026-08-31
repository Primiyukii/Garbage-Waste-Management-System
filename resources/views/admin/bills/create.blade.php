<x-admin-layout title="Generate Bill">
    <div class="max-w-lg bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.bills.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <select name="customer_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                            {{ $customer->user->name }} ({{ $customer->customer_code }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period Start</label>
                    <input type="date" name="period_start" value="{{ old('period_start') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('period_start') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period End</label>
                    <input type="date" name="period_end" value="{{ old('period_end') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('period_end') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <p class="text-xs text-gray-500">Base fee always applies. Additional weight fees are calculated automatically from completed collections within this period, using each collection's matching pricing rule.</p>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.bills.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Generate</button>
            </div>
        </form>
    </div>
</x-admin-layout>
