<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Summary Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .meta { color: #666; font-size: 11px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background: #f5f5f5; font-weight: bold; }
        .summary-box { display: inline-block; padding: 8px 16px; margin-right: 20px; border: 1px solid #ddd; border-radius: 4px; }
        .summary-label { font-size: 10px; color: #666; }
        .summary-value { font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $school->name ?? 'School' }}</h1>
    <p class="meta">Attendance Summary Report &middot; Generated: {{ $generatedAt }}</p>

    <div style="margin-bottom: 20px;">
        <div class="summary-box">
            <div class="summary-label">Total Records</div>
            <div class="summary-value">{{ number_format($summary['total_records']) }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Present</div>
            <div class="summary-value" style="color: #155724;">{{ number_format($summary['present']) }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Absent</div>
            <div class="summary-value" style="color: #721c24;">{{ number_format($summary['absent']) }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Rate</div>
            <div class="summary-value">{{ $summary['attendance_rate'] }}%</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Adm. No.</th>
                <th>Class</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records->take(200) as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $record->student->full_name ?? '—' }}</td>
                    <td>{{ $record->student->admission_number ?? '—' }}</td>
                    <td>{{ $record->schoolClass->name ?? '—' }}</td>
                    <td>{{ $record->attendance_date->format('M d, Y') }}</td>
                    <td style="text-transform: capitalize;">{{ $record->status }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align: center;">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
