<x-collector-layout title="Collection Details">
    <a href="{{ route('collector.schedules.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Back to My Collections</a>

    <div class="bg-white rounded-xl border border-gray-200 p-5 mt-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">{{ $schedule->customer->user->name }}</h2>
            <x-status-badge :status="$schedule->status" />
        </div>

        <dl class="text-sm space-y-2 mb-6">
            <div class="flex justify-between"><dt class="text-gray-500">Address</dt><dd>{{ $schedule->customer->house_number }} {{ $schedule->customer->street }}, {{ $schedule->customer->barangay->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd>{{ $schedule->customer->user->phone ?? '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Service</dt><dd>{{ $schedule->service->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Waste Type</dt><dd>{{ $schedule->wasteType->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Date</dt><dd>{{ $schedule->scheduled_date->format('M d, Y') }}</dd></div>
            @if ($schedule->notes)
                <div class="flex justify-between"><dt class="text-gray-500">Notes</dt><dd>{{ $schedule->notes }}</dd></div>
            @endif
        </dl>

        @if (in_array($schedule->status, ['scheduled', 'assigned']))
            <form method="POST" action="{{ route('collector.schedules.start', $schedule) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Start Collection
                </button>
            </form>
        @endif

        @if ($schedule->status === 'in_progress')
            <form method="POST" action="{{ route('collector.schedules.complete', $schedule) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waste Type Collected</label>
                    <select name="waste_type_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($wasteTypes as $wasteType)
                            <option value="{{ $wasteType->id }}" @selected($wasteType->id === $schedule->waste_type_id)>{{ $wasteType->name }}</option>
                        @endforeach
                    </select>
                    @error('waste_type_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Weight Collected (kg)</label>
                    <input type="number" step="0.01" min="0.01" name="weight_kg" value="{{ old('weight_kg') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('weight_kg') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                    <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Photo (optional)</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-600">
                    @error('photo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
                    Mark Completed
                </button>
            </form>
        @endif

        @if (!in_array($schedule->status, ['completed', 'cancelled', 'missed']))
            <form method="POST" action="{{ route('collector.schedules.missed', $schedule) }}" onsubmit="return confirm('Report this collection as missed?');" class="mt-3">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full py-3 bg-white border border-red-300 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50">
                    Report Missed
                </button>
            </form>
        @endif
    </div>
</x-collector-layout>
