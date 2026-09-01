<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
<div class="min-h-screen flex">
    <div
        x-show="sidebarOpen"
        x-cloak
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
    ></div>

    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-200 transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto"
    >
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <span class="text-white font-semibold text-lg">🗑️ WasteWise</span>
        </div>

        <nav class="px-3 py-4 space-y-1 overflow-y-auto">
            @php
                $navItems = [
                    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'grid'],
                    ['label' => 'Customers', 'route' => 'admin.customers.index', 'icon' => 'users'],
                    ['label' => 'Collectors', 'route' => 'admin.collectors.index', 'icon' => 'truck'],
                    ['label' => 'Locations', 'route' => 'admin.locations.index', 'icon' => 'map-pin'],
                    ['label' => 'Waste Types', 'route' => 'admin.waste-types.index', 'icon' => 'trash'],
                    ['label' => 'Services & Pricing', 'route' => 'admin.services.index', 'icon' => 'tag'],
                    ['label' => 'Schedules', 'route' => 'admin.schedules.index', 'icon' => 'calendar'],
                    ['label' => 'Billing', 'route' => 'admin.bills.index', 'icon' => 'file-text'],
                    ['label' => 'Payments', 'route' => 'admin.payments.index', 'icon' => 'credit-card'],
                    ['label' => 'Reports', 'route' => null, 'icon' => 'bar-chart'],
                    ['label' => 'Audit Logs', 'route' => null, 'icon' => 'shield'],
                    ['label' => 'Settings', 'route' => null, 'icon' => 'settings'],
                ];
            @endphp

            @foreach ($navItems as $item)
                @php
                    $isActive = $item['route'] && request()->routeIs(str_replace('.index', '.*', $item['route']));
                    $isEnabled = $item['route'] !== null;
                @endphp

                <a
                href="{{ $isEnabled ? route($item['route']) : '#' }}"
                @class([
                    'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition',
                    'bg-indigo-600 text-white' => $isActive,
                    'text-slate-300 hover:bg-slate-800 hover:text-white' => ! $isActive && $isEnabled,
                    'text-slate-600 cursor-not-allowed' => ! $isEnabled,
                ])
            >
                <x-dynamic-icon :name="$item['icon']" class="w-5 h-5 shrink-0" />
                <span>{{ $item['label'] }}</span>
                @unless ($isEnabled)
                    <span class="ml-auto text-[10px] uppercase tracking-wide text-slate-500">soon</span>
                    @endunless
                </a>
                    @endforeach
        </nav>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6">
            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <h1 class="text-lg font-semibold text-gray-800 hidden sm:block">{{ $title ?? 'Dashboard' }}</h1>

            <div class="flex items-center gap-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
