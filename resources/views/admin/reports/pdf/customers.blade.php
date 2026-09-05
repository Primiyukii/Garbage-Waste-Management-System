<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
<h1>Customer Report</h1>
<p>{{ $from->format('M d, Y') }} – {{ $to->format('M d, Y') }}</p>
<p>Total: {{ $summary['total'] }} · New: {{ $summary['new_in_period'] }} · Active: {{ $summary['active'] }} · Inactive: {{ $summary['inactive'] }}</p>

<table>
    <thead>
    <tr><th>Code</th><th>Name</th><th>Barangay</th><th>Status</th><th>Registered</th></tr>
    </thead>
    <tbody>
    @foreach ($rows as $customer)
        <tr>
            <td>{{ $customer->customer_code }}</td>
            <td>{{ $customer->user->name }}</td>
            <td>{{ $customer->barangay->name }}</td>
            <td>{{ ucfirst($customer->user->status) }}</td>
            <td>{{ $customer->registered_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
