<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Collector' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
<header class="bg-blue-900 text-white">
    <div class="max-w-3xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="{{ route('collector.dashboard') }}" class="font-semibold text-lg">🗑️ WasteWise</a>

        <nav class="hidden sm:flex items-center gap-6 text-sm">
            <a href="{{ route('collector.dashboard') }}" class="{{ request()->routeIs('collector.dashboard') ? 'text-white' : 'text-blue-100 hover:text-white' }}">Dashboard</a>
            <a href="{{ route('collector.schedules.index') }}" class="{{ request()->routeIs('collector.schedules.*') ? 'text-white' : 'text-blue-100 hover:text-white' }}">My Collections</a>
        </nav>

        <x-notification-bell />
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 text-sm">
                <span>{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 text-gray-700 text-sm z-50">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Log Out</button>
                </form>
            </div>
        </div>
    </div>

    <nav class="sm:hidden flex items-center justify-around border-t border-blue-800 text-xs">
        <a href="{{ route('collector.dashboard') }}" class="flex-1 text-center py-2 {{ request()->routeIs('collector.dashboard') ? 'text-white' : 'text-blue-100' }}">Dashboard</a>
        <a href="{{ route('collector.schedules.index') }}" class="flex-1 text-center py-2 {{ request()->routeIs('collector.schedules.*') ? 'text-white' : 'text-blue-100' }}">My Collections</a>
    </nav>
</header>

@if (session('success'))
    <div class="max-w-3xl mx-auto px-4 mt-4">
        <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    </div>
@endif

<main class="max-w-3xl mx-auto px-4 py-6">
    {{ $slot }}
</main>
</body>
</html>
