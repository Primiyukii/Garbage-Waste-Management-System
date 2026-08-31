<x-admin-layout title="Collection Schedules">
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <form method="GET" class="flex flex-1 flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer name..." class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    @foreach (['scheduled','assigned','in_progress','completed','missed','cancelled'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ Str::headline($status) }}</option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
                @if (request('search') || request('status') || request('date'))
                    <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>
            <a href="{{ route('admin.schedules.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 whitespace-nowrap">
                Add Schedule
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-left">Collector</th>
                    <th class="px-4 py-3 text-left">Service / Waste</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($schedules as $schedule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $schedule->customer->user->name }}</td>
                        <td class="px-4 py-3" x-data="{ assignOpen: false }">
                            @if ($schedule->collector)
                                {{ $schedule->collector->user->name }}
                            @else
                                <button @click="assignOpen = true" type="button" class="text-indigo-600 hover:text-indigo-800 text-xs">Assign</button>
                            @endif

                            <div x-show="assignOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="assignOpen = false" @keydown.escape.window="assignOpen = false">
                                <div class="bg-white rounded-xl p-6 w-full max-w-sm" @click.stop>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Collector</h3>
                                    <form method="POST" action="{{ route('admin.schedules.assign-collector', $schedule) }}" class="space-y-4">
                                        @csrf
                                        @method('PATCH')
                                        <select name="collector_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Select a collector</option>
                                            @foreach ($collectors as $collector)
                                                <option value="{{ $collector->id }}">{{ $collector->user->name }} ({{ $collector->employee_id }})</option>
                                            @endforeach
                                        </select>
                                        <div class="flex justify-end gap-3 pt-2">
                                            <button type="button" @click="assignOpen = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Assign</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $schedule->service->name }} — {{ $schedule->wasteType->name }}</td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $schedule->scheduled_date->format('M d, Y') }}
                            @if ($schedule->scheduled_time) {{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') }} @endif
                        </td>
                        <td class="px-4 py-3"><x-status-badge :status="$schedule->status" /></td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-gray-600 hover:text-gray-800">Edit</a>
                                @if (!in_array($schedule->status, ['completed', 'cancelled']))
                                    <form method="POST" action="{{ route('admin.schedules.cancel', $schedule) }}" onsubmit="return confirm('Cancel this schedule?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">No schedules found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $schedules->links() }}
        </div>
    </div>
</x-admin-layout>
