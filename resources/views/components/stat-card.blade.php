@props(['label', 'value', 'icon', 'color' => 'indigo'])

@php
    $colorMap = [
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'green' => 'bg-green-50 text-green-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'red' => 'bg-red-50 text-red-600',
    ];
@endphp

<div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
    <div class="{{ $colorMap[$color] }} rounded-lg p-3 shrink-0">
        <x-dynamic-icon :name="$icon" class="w-6 h-6" />
    </div>
    <div class="min-w-0">
        <p class="text-sm text-gray-500 truncate">{{ $label }}</p>
        <p class="text-2xl font-semibold text-gray-900">{{ $value }}</p>
    </div>
</div>
