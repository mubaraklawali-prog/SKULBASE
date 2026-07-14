<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment History Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .meta { color: #666; font-size: 11px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background: #f5f5f5; font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $school->name ?? 'School' }}</h1>
    <p class="meta">Payment History Report &middot; Generated: {{ $generatedAt }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Adm. No.</th>
                <th>Class</th>
                <th>Fee Title</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payment->student->full_name ?? '—' }}</td>
                    <td>{{ $payment->student->admission_number ?? '—' }}</td>
                    <td>{{ $payment->feeStructure->schoolClass->name ?? '—' }}</td>
                    <td>{{ $payment->feeStructure->title ?? '—' }}</td>
                    <td>&#8358;{{ number_format($payment->amount_paid, 2) }}</td>
                    <td style="text-transform: capitalize;">{{ $payment->payment_method }}</td>
                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align: center;">No payments found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
