<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student List Report</title>
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
    <p class="meta">Student List Report &middot; Generated: {{ $generatedAt }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Adm. No.</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Class</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->admission_number }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ ucfirst($student->gender) }}</td>
                    <td>{{ $student->schoolClass->name ?? '—' }}</td>
                    <td>{{ $student->email ?? '—' }}</td>
                    <td>{{ $student->phone ?? '—' }}</td>
                    <td><span class="badge {{ $student->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ ucfirst($student->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align: center;">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
