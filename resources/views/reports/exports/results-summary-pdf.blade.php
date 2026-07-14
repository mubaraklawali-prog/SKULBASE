<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Results Summary Report</title>
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
    <p class="meta">Results Summary Report &middot; Generated: {{ $generatedAt }}</p>

    @if($summary)
        <div style="margin-bottom: 20px;">
            <div class="summary-box">
                <div class="summary-label">Exam</div>
                <div class="summary-value">{{ $summary['exam']->name ?? 'N/A' }}</div>
            </div>
            <div class="summary-box">
                <div class="summary-label">Students</div>
                <div class="summary-value">{{ $summary['total_students'] }}</div>
            </div>
            <div class="summary-box">
                <div class="summary-label">Average</div>
                <div class="summary-value">{{ $summary['average_score'] }}%</div>
            </div>
            <div class="summary-box">
                <div class="summary-label">Pass Rate</div>
                <div class="summary-value">{{ $summary['pass_rate'] }}%</div>
            </div>
        </div>

        @if($summary['subject_averages']->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Records</th>
                        <th>Average</th>
                        <th>Highest</th>
                        <th>Lowest</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary['subject_averages'] as $item)
                        <tr>
                            <td>{{ $item['subject']->name ?? '—' }}</td>
                            <td>{{ $item['count'] }}</td>
                            <td>{{ $item['average'] }}%</td>
                            <td>{{ $item['highest'] }}%</td>
                            <td>{{ $item['lowest'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <p>No results data available.</p>
    @endif
</body>
</html>
