@props(['status'])

@php
    $styles = [
        'active' => 'bg-green-100 text-green-700',
        'inactive' => 'bg-gray-100 text-gray-700',
        'suspended' => 'bg-red-100 text-red-700',
        'scheduled' => 'bg-blue-100 text-blue-700',
        'assigned' => 'bg-indigo-100 text-indigo-700',
        'in_progress' => 'bg-amber-100 text-amber-700',
        'completed' => 'bg-green-100 text-green-700',
        'missed' => 'bg-red-100 text-red-700',
        'cancelled' => 'bg-gray-100 text-gray-700',
        'paid' => 'bg-green-100 text-green-700',
        'unpaid' => 'bg-amber-100 text-amber-700',
        'partially_paid' => 'bg-amber-100 text-amber-700',
        'overdue' => 'bg-red-100 text-red-700',
    ];
    $label = ucwords(str_replace('_', ' ', $status));
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $styles[$status] ?? 'bg-gray-100 text-gray-700' }}">
    {{ $label }}
</span>
