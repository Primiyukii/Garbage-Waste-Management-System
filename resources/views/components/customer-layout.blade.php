<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'My Account' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
<header class="bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="{{ route('customer.dashboard') }}" class="font-semibold text-lg text-gray-900">🗑️ WasteWise</a>

        <nav class="hidden sm:flex items-center gap-6 text-sm">
            <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'text-indigo-600 font-medium' : 'text-gray-500 hover:text-gray-900' }}">Dashboard</a>
            <a href="{{ route('customer.schedule.index') }}" class="{{ request()->routeIs('customer.schedule.*') ? 'text-indigo-600 font-medium' : 'text-gray-500 hover:text-gray-900' }}">Schedule</a>
            <a href="{{ route('customer.bills.index') }}" class="{{ request()->routeIs('customer.bills.*') ? 'text-indigo-600 font-medium' : 'text-gray-500 hover:text-gray-900' }}">Bills</a>
                <a href="{{ route('customer.payments.index') }}" class="{{ request()->routeIs('customer.payments.*') ? 'text-indigo-600 font-medium' : 'text-gray-500 hover:text-gray-900' }}">Payments</a>
        </nav>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-700">
                <span>{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 text-gray-700 text-sm z-50 border border-gray-100">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Log Out</button>
                </form>
            </div>
        </div>
    </div>

    <nav class="sm:hidden flex items-center justify-around border-t border-gray-100 text-xs">
        <a href="{{ route('customer.dashboard') }}" class="flex-1 text-center py-2 {{ request()->routeIs('customer.dashboard') ? 'text-indigo-600 font-medium' : 'text-gray-500' }}">Dashboard</a>
        <a href="{{ route('customer.schedule.index') }}" class="flex-1 text-center py-2 {{ request()->routeIs('customer.schedule.*') ? 'text-indigo-600 font-medium' : 'text-gray-500' }}">Schedule</a>
        <a href="{{ route('customer.bills.index') }}" class="flex-1 text-center py-2 {{ request()->routeIs('customer.bills.*') ? 'text-indigo-600 font-medium' : 'text-gray-500' }}">Bills</a>
            <a href="{{ route('customer.payments.index') }}" class="flex-1 text-center py-2 {{ request()->routeIs('customer.payments.*') ? 'text-indigo-600 font-medium' : 'text-gray-500' }}">Payments</a>
    </nav>
</header>

@if (session('success'))
    <div class="max-w-4xl mx-auto px-4 mt-4">
        <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    </div>
@endif

<main class="max-w-4xl mx-auto px-4 py-6">
    {{ $slot }}
</main>
</body>
</html>
