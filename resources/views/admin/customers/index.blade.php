<x-admin-layout title="Customers">
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <form method="GET" class="flex flex-1 gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search name, email, or phone..."
                    class="flex-1 max-w-sm rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                    Filter
                </button>
                @if (request('search') || request('status'))
                    <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>

            <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 whitespace-nowrap">
                Add Customer
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-left">Contact</th>
                    <th class="px-4 py-3 text-left">Barangay</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Registered</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $customer->user->name }}</div>
                            <div class="text-gray-500 text-xs">{{ $customer->customer_code }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            <div>{{ $customer->user->email }}</div>
                            <div class="text-xs text-gray-500">{{ $customer->user->phone ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->barangay->name }}</td>
                        <td class="px-4 py-3">
                            <x-status-badge :status="$customer->user->status" />
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $customer->registered_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="text-gray-600 hover:text-gray-800">Edit</a>
                                <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}" onsubmit="return confirm('Change this customer status?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-amber-600 hover:text-amber-800">
                                        {{ $customer->user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">No customers found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $customers->links() }}
        </div>
    </div>
</x-admin-layout>
