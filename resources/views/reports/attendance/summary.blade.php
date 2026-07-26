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
            <a href="{{ route('reports.export.attendance.csv', request()->query()) }}" class="sb-btn sb-btn-primary">Export CSV</a>
            <a href="{{ route('reports.export.attendance.pdf', request()->query()) }}" class="sb-btn sb-btn-danger">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="sb-btn sb-btn-secondary">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.attendance.summary') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label class="sb-form-label">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="sb-form-input">
                </div>
                <div style="flex: 1;">
                    <label class="sb-form-label">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="sb-form-input">
                </div>
                <div style="flex: 1;">
                    <label class="sb-form-label">Class</label>
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #0d6efd;">{{ number_format($total_records) }}</p>
                    <p class="stat-label">Total Records</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">{{ number_format($present) }}</p>
                    <p class="stat-label">Present</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">{{ number_format($absent) }}</p>
                    <p class="stat-label">Absent</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: {{ $attendance_rate >= 70 ? '#0f5132' : '#dc3545' }};">{{ $attendance_rate }}%</p>
                    <p class="stat-label">Attendance Rate</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
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
                        @forelse($records->take(100) as $index => $record)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="font-weight: 500;">{{ $record->student->full_name ?? '—' }}</td>
                                <td><code>{{ $record->student->admission_number ?? '—' }}</code></td>
                                <td>{{ $record->schoolClass->name ?? '—' }}</td>
                                <td>{{ $record->attendance_date->format('M d, Y') }}</td>
                                <td>
                                    @if($record->status === 'present')
                                        <span class="sb-badge sb-badge-present">Present</span>
                                    @elseif($record->status === 'absent')
                                        <span class="sb-badge sb-badge-absent">Absent</span>
                                    @elseif($record->status === 'late')
                                        <span class="sb-badge sb-badge-late">Late</span>
                                    @else
                                        <span class="sb-badge sb-badge-excused">Excused</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="sb-empty-state">No attendance records found.</td>
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
