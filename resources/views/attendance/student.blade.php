@extends('layouts.app')

@section('title', $student->full_name . ' Attendance - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $student->full_name }}</h2>
            <p class="text-muted mb-0">Attendance History &middot; {{ $student->schoolClass->name ?? 'No Class' }}</p>
        </div>
        <a href="{{ route('attendance.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            Back to Records
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 16px;">
                    <p class="stat-number" style="font-size: 24px; color: #1a1a2e;">{{ $totalDays }}</p>
                    <p class="stat-label">Total Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 16px;">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">{{ $presentDays }}</p>
                    <p class="stat-label">Present</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 16px;">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">{{ $absentDays }}</p>
                    <p class="stat-label">Absent</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 16px;">
                    <p class="stat-number" style="font-size: 24px; color: #664d03;">{{ $lateDays }}</p>
                    <p class="stat-label">Late</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 16px;">
                    <p class="stat-number" style="font-size: 24px; color: #0d6efd;">{{ $excusedDays }}</p>
                    <p class="stat-label">Excused</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 16px;">
                    <p class="stat-number" style="font-size: 24px; color: {{ $attendancePercentage >= 75 ? '#0f5132' : '#842029' }};">{{ $attendancePercentage }}%</p>
                    <p class="stat-label">Attendance</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
                <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">Attendance Records</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Remarks</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Marked By</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $record)
                            <tr>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $record->attendance_date->format('M d, Y') }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $record->schoolClass->name ?? '—' }}</td>
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
                                <td style="padding: 14px 20px; color: #6c757d; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $record->remarks ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $record->marker->full_name ?? '—' }}</td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <a href="{{ route('attendance.show', $record) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No attendance records found for this student.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($attendances->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
@endsection
