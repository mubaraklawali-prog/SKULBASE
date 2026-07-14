@extends('layouts.app')

@section('title', 'Attendance Summary - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Attendance Summary</h2>
            <p class="text-muted mb-0">Overall attendance records</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export.attendance.csv', request()->query()) }}" class="btn" style="background: #198754; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Export CSV</a>
            <a href="{{ route('reports.export.attendance.pdf', request()->query()) }}" class="btn" style="background: #dc3545; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.attendance.summary') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Class</label>
                    <select name="class_id" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">Filter</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #0d6efd;">{{ number_format($total_records) }}</p>
                    <p class="stat-label">Total Records</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">{{ number_format($present) }}</p>
                    <p class="stat-label">Present</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">{{ number_format($absent) }}</p>
                    <p class="stat-label">Absent</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: {{ $attendance_rate >= 70 ? '#0f5132' : '#dc3545' }};">{{ $attendance_rate }}%</p>
                    <p class="stat-label">Attendance Rate</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Adm. No.</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records->take(100) as $index => $record)
                            <tr>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $index + 1 }}</td>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $record->student->full_name ?? '—' }}</td>
                                <td style="padding: 14px 20px;"><code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $record->student->admission_number ?? '—' }}</code></td>
                                <td style="padding: 14px 20px;">{{ $record->schoolClass->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">{{ $record->attendance_date->format('M d, Y') }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($record->status === 'present')
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Present</span>
                                    @elseif($record->status === 'absent')
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Absent</span>
                                    @elseif($record->status === 'late')
                                        <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Late</span>
                                    @else
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Excused</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($records->count() > 100)
            <div class="card-body" style="border-top: 1px solid #f0f2f5;">
                <small class="text-muted">Showing first 100 records. Export to CSV/PDF for complete data.</small>
            </div>
        @endif
    </div>
</div>
@endsection
