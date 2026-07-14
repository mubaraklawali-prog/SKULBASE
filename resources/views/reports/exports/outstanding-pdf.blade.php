<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Outstanding Fees Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .meta { color: #666; font-size: 11px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background: #f5f5f5; font-weight: bold; }
        .summary-box { display: inline-block; padding: 8px 16px; margin-right: 20px; border: 1px solid #ddd; border-radius: 4px; }
        .summary-label { font-size: 10px; color: #666; }
        .summary-value { font-size: 16px; font-weight: bold; color: #721c24; }
    </style>
</head>
<body>
    <h1>{{ $school->name ?? 'School' }}</h1>
    <p class="meta">Outstanding Fees Report &middot; Generated: {{ $generatedAt }}</p>

    <div style="margin-bottom: 20px;">
        <div class="summary-box">
            <div class="summary-label">Students with Balance</div>
            <div class="summary-value">{{ count($items) }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Total Outstanding</div>
            <div class="summary-value">&#8358;{{ number_format($totalOutstanding, 2) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Adm. No.</th>
                <th>Class</th>
                <th>Total Fees</th>
                <th>Paid</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['student']->full_name }}</td>
                    <td>{{ $item['student']->admission_number }}</td>
                    <td>{{ $item['student']->schoolClass->name ?? '—' }}</td>
                    <td>&#8358;{{ number_format($item['total_fees'], 2) }}</td>
                    <td>&#8358;{{ number_format($item['total_paid'], 2) }}</td>
                    <td style="font-weight: bold; color: #721c24;">&#8358;{{ number_format($item['balance'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align: center;">No outstanding balances.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
