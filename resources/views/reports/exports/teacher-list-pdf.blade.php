<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teacher List Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .meta { color: #666; font-size: 11px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background: #f5f5f5; font-weight: bold; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-inactive { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>{{ $school->name ?? 'School' }}</h1>
    <p class="meta">Teacher List Report &middot; Generated: {{ $generatedAt }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Qualification</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $index => $teacher)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $teacher->full_name }}</td>
                    <td>{{ ucfirst($teacher->gender) }}</td>
                    <td>{{ $teacher->email ?? '—' }}</td>
                    <td>{{ $teacher->phone }}</td>
                    <td>{{ $teacher->qualification ?? '—' }}</td>
                    <td><span class="badge {{ $teacher->status ? 'badge-active' : 'badge-inactive' }}">{{ $teacher->status ? 'Active' : 'Inactive' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align: center;">No teachers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
