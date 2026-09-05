@php
    $notifications = auth()->user()->unreadNotifications()->take(5)->get();
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative text-gray-500 hover:text-gray-900">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
        </svg>
        @if ($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">{{ $unreadCount }}</span>
        @endif
    </button>

    <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-100 py-1 text-sm z-50">
        @forelse ($notifications as $notification)
            <div class="px-4 py-3 border-b border-gray-50 last:border-0">
                <p class="text-gray-700">{{ $notification->data['message'] }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="px-4 py-6 text-center text-gray-500">No new notifications.</p>
        @endforelse
    </div>
</div>
