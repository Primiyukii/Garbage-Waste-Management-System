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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Monthly Revenue ({{ now()->year }})</h2>
            <canvas id="revenueChart" height="220"></canvas>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Collections: Completed vs Pending</h2>
            <canvas id="collectionStatusChart" height="220"></canvas>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
        <script>
            const revenueData = @json($revenueChart);
            const collectionStatusData = @json($collectionStatusChart);

            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: revenueData,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.3,
                        fill: true,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } },
                },
            });

            new Chart(document.getElementById('collectionStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Pending'],
                    datasets: [{
                        data: [collectionStatusData.completed, collectionStatusData.pending],
                        backgroundColor: ['#16a34a', '#f59e0b'],
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                },
            });
        </script>
    @endpush
</x-admin-layout>
