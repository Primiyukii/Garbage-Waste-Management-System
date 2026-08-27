<x-admin-layout title="Dashboard">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card label="Total Customers" :value="$stats['total_customers']" icon="users" />
        <x-stat-card label="Active Customers" :value="$stats['active_customers']" icon="users" color="green" />
        <x-stat-card label="Total Collectors" :value="$stats['total_collectors']" icon="truck" />
        <x-stat-card label="Active Collectors" :value="$stats['active_collectors']" icon="truck" color="green" />

        <x-stat-card label="Today's Collections" :value="$stats['todays_collections']" icon="calendar" />
        <x-stat-card label="Completed Collections" :value="$stats['completed_collections']" icon="calendar" color="green" />
        <x-stat-card label="Pending Collections" :value="$stats['pending_collections']" icon="calendar" color="amber" />

        <x-stat-card label="Total Bills" :value="$stats['total_bills']" icon="file-text" />
        <x-stat-card label="Paid Bills" :value="$stats['paid_bills']" icon="file-text" color="green" />
        <x-stat-card label="Unpaid Bills" :value="$stats['unpaid_bills']" icon="file-text" color="amber" />
        <x-stat-card label="Overdue Bills" :value="$stats['overdue_bills']" icon="file-text" color="red" />

        <x-stat-card label="Total Revenue" :value="'₱' . number_format($stats['total_revenue'], 2)" icon="credit-card" color="green" />
        <x-stat-card label="This Month's Revenue" :value="'₱' . number_format($stats['monthly_revenue'], 2)" icon="credit-card" color="green" />
    </div>
</x-admin-layout>
