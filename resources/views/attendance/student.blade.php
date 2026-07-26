@extends('layouts.app')

@section('title', $student->full_name . ' Attendance - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $student->full_name }}</h2>
            <p class="text-muted mb-0">Attendance History &middot; {{ $student->schoolClass->name ?? 'No Class' }}</p>
        </div>
        <a href="{{ route('attendance.index') }}" class="sb-btn sb-btn-ghost">
            Back to Records
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body text-center p-3">
                    <p class="stat-number sb-stat-number">{{ $totalDays }}</p>
                    <p class="stat-label">Total Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body text-center p-3">
                    <p class="stat-number sb-stat-number sb-stat-number-present">{{ $presentDays }}</p>
                    <p class="stat-label">Present</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body text-center p-3">
                    <p class="stat-number sb-stat-number sb-stat-number-absent">{{ $absentDays }}</p>
                    <p class="stat-label">Absent</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body text-center p-3">
                    <p class="stat-number sb-stat-number sb-stat-number-late">{{ $lateDays }}</p>
                    <p class="stat-label">Late</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body text-center p-3">
                    <p class="stat-number sb-stat-number sb-stat-number-excused">{{ $excusedDays }}</p>
                    <p class="stat-label">Excused</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body text-center p-3">
                    <p class="stat-number sb-stat-number {{ $attendancePercentage >= 75 ? 'sb-stat-number-present' : 'sb-stat-number-absent' }}">{{ $attendancePercentage }}%</p>
                    <p class="stat-label">Attendance</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="sb-section-header">
                <h5>Attendance Records</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 sb-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Marked By</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $record)
                            <tr>
                                <td class="text-muted">{{ $record->attendance_date->format('M d, Y') }}</td>
                                <td class="text-muted">{{ $record->schoolClass->name ?? '—' }}</td>
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
                                <td class="text-muted text-truncate" style="max-width: 150px;">{{ $record->remarks ?? '—' }}</td>
                                <td class="text-muted">{{ $record->marker->full_name ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('attendance.show', $record) }}" class="sb-link">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="sb-empty-state">
                                        <p>No attendance records found for this student.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($attendances->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
@endsection