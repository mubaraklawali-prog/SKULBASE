<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable - {{ $student->full_name }} - Skulbase</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #333;
            background: #fff;
            padding: 24px;
        }
        .print-header {
            text-align: center;
            margin-bottom: 24px;
            border-bottom: 2px solid #0a1628;
            padding-bottom: 16px;
        }
        .print-header .school-logo {
            width: 60px;
            height: 60px;
            background: #f0f2f5;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }
        .print-header .school-logo svg {
            width: 30px;
            height: 30px;
            stroke: #0a1628;
        }
        .print-header .school-name {
            font-size: 22px;
            font-weight: 700;
            color: #0a1628;
            margin-bottom: 4px;
        }
        .print-header .timetable-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 4px;
        }
        .print-header .student-info {
            font-size: 14px;
            color: #6c757d;
        }
        .print-header .student-info strong {
            color: #333;
        }
        .print-actions {
            text-align: right;
            margin-bottom: 16px;
        }
        .print-actions button {
            background: #0a1628;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }
        .print-actions button:hover { background: #162240; }
        .timetable-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .timetable-table thead th {
            background: #0a1628;
            color: #fff;
            padding: 10px 8px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .timetable-table thead th:first-child {
            text-align: left;
            min-width: 120px;
        }
        .timetable-table tbody td {
            padding: 8px;
            border: 1px solid #e9ecef;
            vertical-align: top;
            min-height: 60px;
        }
        .timetable-table tbody td:first-child {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 12px;
            white-space: nowrap;
        }
        .timetable-table tbody tr:nth-child(even) td:not(:first-child) {
            background: #fafbfc;
        }
        .lesson-cell .subject {
            font-weight: 600;
            font-size: 12px;
            color: #1a1a2e;
        }
        .lesson-cell .teacher {
            font-size: 11px;
            color: #6c757d;
            margin-top: 2px;
        }
        .lesson-cell .room {
            font-size: 10px;
            color: #adb5bd;
            margin-top: 1px;
        }
        .break-cell, .lunch-cell, .assembly-cell {
            text-align: center;
            font-weight: 600;
            font-size: 11px;
            padding: 12px 8px;
        }
        .break-cell { background: #fff8e1 !important; color: #664d03; }
        .lunch-cell { background: #e8f5e9 !important; color: #0f5132; }
        .assembly-cell { background: #f3e5f5 !important; color: #6f42c1; }
        .free-cell {
            text-align: center;
            color: #ced4da;
            font-style: italic;
            font-size: 11px;
            padding: 12px 8px;
        }
        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 11px;
            color: #adb5bd;
            border-top: 1px solid #e9ecef;
            padding-top: 12px;
        }
        @media print {
            body { padding: 0; font-size: 11px; }
            .print-actions { display: none !important; }
            .print-header { border-bottom-width: 1px; }
            .timetable-table thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .timetable-table tbody td:first-child { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .break-cell, .lunch-cell, .assembly-cell { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { size: landscape; margin: 10mm; }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button onclick="window.print();">Print Timetable</button>
    </div>

    <div class="print-header">
        <div class="school-logo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
            </svg>
        </div>
        <div class="school-name">{{ $school->name ?? 'School Name' }}</div>
        <div class="timetable-title">Student Weekly Timetable</div>
        <div class="student-info">
            Student: <strong>{{ $student->full_name ?? '—' }}</strong>
            | Class: <strong>{{ $schoolClass->name ?? '—' }}</strong>
            | Section: <strong>{{ $section->name ?? '—' }}</strong>
            | Generated: <strong>{{ now()->format('M d, Y') }}</strong>
        </div>
    </div>

    <table class="timetable-table">
        <thead>
            <tr>
                <th>Period</th>
                @foreach($days as $day)
                    <th>{{ strtoupper(substr($day, 0, 3)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($periods as $period)
                <tr>
                    <td>
                        {{ $period->name }}
                        @if($period->start_time && $period->end_time)
                            <br><small style="font-weight: 400; color: #6c757d;">
                                {{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}
                            </small>
                        @endif
                    </td>
                    @foreach($days as $day)
                        @php
                            $key = $period->id . '_' . $day;
                            $entry = $grid->get($key);
                        @endphp
                        <td>
                            @if(in_array($period->type, ['break', 'lunch', 'assembly']))
                                <div class="{{ $period->type }}-cell">{{ ucfirst($period->type) }}</div>
                            @elseif($entry)
                                <div class="lesson-cell">
                                    <div class="subject">{{ $entry->subject->name ?? '—' }}</div>
                                    <div class="teacher">{{ $entry->teacher->full_name ?? '—' }}</div>
                                    @if($entry->notes)
                                        <div class="room">{{ $entry->notes }}</div>
                                    @endif
                                </div>
                            @else
                                <div class="free-cell">—</div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($days) + 1 }}" style="text-align: center; padding: 30px; color: #6c757d;">
                        No timetable data available.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        {{ $school->name ?? 'Skulbase' }} &mdash; Student Weekly Timetable &mdash; Generated on {{ now()->format('M d, Y \a\t h:i A') }}
    </div>
</body>
</html>
