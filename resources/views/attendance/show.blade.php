@extends('layouts.app')

@section('title', 'Attendance Record - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Attendance Detail</h2>
            <p class="text-muted mb-0">{{ $attendance->student->full_name }} &middot; {{ $attendance->attendance_date->format('l, M d, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('attendance.student', $attendance->student) }}" class="sb-btn sb-btn-outline-primary">Student History</a>
            <a href="{{ route('attendance.index') }}" class="sb-btn sb-btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Attendance Details</h5>
                    <div class="mb-3">
                        <label class="sb-form-label">Status</label>
                        <p style="margin: 0;">
                            @if($attendance->status === 'present')
                                <span class="sb-badge sb-badge-present">Present</span>
                            @elseif($attendance->status === 'absent')
                                <span class="sb-badge sb-badge-absent">Absent</span>
                            @elseif($attendance->status === 'late')
                                <span class="sb-badge sb-badge-late">Late</span>
                            @else
                                <span class="sb-badge sb-badge-excused">Excused</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Date</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->attendance_date->format('l, M d, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Class</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->schoolClass->name ?? '—' }}{{ $attendance->schoolClass->section ? ' - ' . $attendance->schoolClass->section : '' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Remarks</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->remarks ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="sb-form-label">Marked By</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->marker->full_name ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Student Info</h5>
                    <div class="mb-3">
                        <label class="sb-form-label">Full Name</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->student->full_name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Admission Number</label>
                        <p style="margin: 0; font-size: 15px;"><code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $attendance->student->admission_number }}</code></p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">School</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->school->name ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="sb-form-label">Student Status</label>
                        <p style="margin: 0;">
                            @if($attendance->student->status === 'active')
                                <span class="sb-badge sb-badge-active">Active</span>
                            @else
                                <span class="sb-badge sb-badge-inactive">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
