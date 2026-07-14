<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Subject Performance Report</title>
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
    <p class="meta">Subject Performance Report &middot; Generated: {{ $generatedAt }}</p>

    @if($performance->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Students</th>
                    <th>Average</th>
                    <th>Pass Rate</th>
                    <th>Highest</th>
                    <th>Lowest</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performance as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['subject']->name }}</td>
                        <td>{{ $item['students'] }}</td>
                        <td>{{ $item['average'] }}%</td>
                        <td>{{ $item['pass_rate'] }}%</td>
                        <td>{{ $item['highest'] }}%</td>
                        <td>{{ $item['lowest'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No subject performance data available.</p>
    @endif
</body>
</html>
