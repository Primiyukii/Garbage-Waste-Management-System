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
<h1>Financial Report</h1>
<p>{{ $from->format('M d, Y') }} – {{ $to->format('M d, Y') }}</p>
<p>Billed: ₱{{ number_format($summary['total_billed'], 2) }} · Paid: ₱{{ number_format($summary['total_paid'], 2) }} · Unpaid: ₱{{ number_format($summary['total_unpaid'], 2) }} · Overdue: ₱{{ number_format($summary['total_overdue'], 2) }}</p>

<table>
    <thead>
    <tr><th>Bill Number</th><th>Customer</th><th>Total</th><th>Paid</th><th>Status</th></tr>
    </thead>
    <tbody>
    @foreach ($rows as $bill)
        <tr>
            <td>{{ $bill->bill_number }}</td>
            <td>{{ $bill->customer->user->name }}</td>
            <td>₱{{ number_format($bill->total_amount, 2) }}</td>
            <td>₱{{ number_format($bill->amount_paid, 2) }}</td>
            <td>{{ ucfirst($bill->status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
