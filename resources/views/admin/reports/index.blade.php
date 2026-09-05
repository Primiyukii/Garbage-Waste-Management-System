<x-admin-layout title="Reports">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach (['customers' => 'Customer Reports', 'collections' => 'Collection Reports', 'waste' => 'Waste Reports', 'financial' => 'Financial Reports'] as $type => $label)
            <a href="{{ route('admin.reports.show', $type) }}" class="bg-white rounded-xl border border-gray-200 p-6 hover:border-indigo-300 hover:shadow-sm transition">
                <h3 class="font-semibold text-gray-900">{{ $label }}</h3>
                <p class="text-sm text-gray-500 mt-1">View, filter, and export {{ strtolower($label) }}.</p>
            </a>
        @endforeach
    </div>
</x-admin-layout>
