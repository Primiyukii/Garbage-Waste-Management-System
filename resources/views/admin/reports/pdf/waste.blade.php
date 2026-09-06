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
<h1>Waste Report</h1>
<p>{{ $from->format('M d, Y') }} – {{ $to->format('M d, Y') }}</p>
<p>Total: {{ number_format($summary['total_kg'], 2) }} kg across {{ $summary['total_records'] }} records</p>

<table>
    <thead>
    <tr><th>Waste Type</th><th>Total Kg</th><th>Collections</th></tr>
    </thead>
    <tbody>
    @foreach ($rows as $row)
        <tr>
            <td>{{ $row['waste_type'] }}</td>
            <td>{{ number_format($row['total_kg'], 2) }}</td>
            <td>{{ $row['collections'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
