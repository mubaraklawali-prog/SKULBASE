@extends('layouts.app')

@section('title', 'Attendance Record - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Attendance Detail</h2>
            <p class="text-muted mb-0">{{ $attendance->student->full_name }} &middot; {{ $attendance->attendance_date->format('l, M d, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('attendance.student', $attendance->student) }}" class="btn" style="background: #e7f1ff; color: #0d6efd; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                Student History
            </a>
            <a href="{{ route('attendance.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Attendance Details</h5>
                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Status</label>
                        <p style="margin: 0;">
                            @if($attendance->status === 'present')
                                <span style="background: #d1e7dd; color: #0f5132; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">Present</span>
                            @elseif($attendance->status === 'absent')
                                <span style="background: #f8d7da; color: #842029; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">Absent</span>
                            @elseif($attendance->status === 'late')
                                <span style="background: #fff3cd; color: #664d03; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">Late</span>
                            @else
                                <span style="background: #e7f1ff; color: #0d6efd; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">Excused</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Date</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->attendance_date->format('l, M d, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Class</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->schoolClass->name ?? '—' }}{{ $attendance->schoolClass->section ? ' - ' . $attendance->schoolClass->section : '' }}</p>
                    </div>
                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Remarks</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->remarks ?? '—' }}</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Marked By</label>
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
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Full Name</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->student->full_name }}</p>
                    </div>
                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Admission Number</label>
                        <p style="margin: 0; font-size: 15px;"><code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $attendance->student->admission_number }}</code></p>
                    </div>
                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">School</label>
                        <p style="margin: 0; font-size: 15px;">{{ $attendance->school->name ?? '—' }}</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Student Status</label>
                        <p style="margin: 0;">
                            @if($attendance->student->status === 'active')
                                <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                            @else
                                <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
