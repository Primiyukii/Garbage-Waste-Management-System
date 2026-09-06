<x-admin-layout title="Audit Logs">
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <form method="GET" class="flex flex-wrap gap-2">
                <input type="text" name="action" value="{{ request('action') }}" placeholder="Filter by action (e.g. customer, bill, auth)..." class="flex-1 max-w-sm rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
                @if (request('action'))
                    <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">User</th>
                    <th class="px-4 py-3 text-left">Action</th>
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-left">IP Address</th>
                    <th class="px-4 py-3 text-left">When</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-700">{{ $log->action }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $log->description }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $log->ip_address }}</td>
                        <td class="px-4 py-3 text-gray-500" title="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">No audit log entries yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">{{ $logs->links() }}</div>
    </div>
</x-admin-layout>
