<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        h1 { font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
<h1>Collection Report</h1>
<p>{{ $from->format('M d, Y') }} – {{ $to->format('M d, Y') }}</p>
<p>Total: {{ $summary['total'] }} · Completed: {{ $summary['completed'] }} · Missed: {{ $summary['missed'] }} · Cancelled: {{ $summary['cancelled'] }}</p>

<table>
    <thead>
    <tr><th>Date</th><th>Customer</th><th>Collector</th><th>Status</th></tr>
    </thead>
    <tbody>
    @foreach ($rows as $schedule)
        <tr>
            <td>{{ $schedule->scheduled_date->format('Y-m-d') }}</td>
            <td>{{ $schedule->customer->user->name }}</td>
            <td>{{ $schedule->collector->user->name ?? 'Unassigned' }}</td>
            <td>{{ ucfirst($schedule->status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
