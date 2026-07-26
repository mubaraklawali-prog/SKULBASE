@extends('layouts.app')

@section('title', 'My Attendance - Skulbase')

@section('content')
<style>
    .stat-card-mini {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s;
    }
    .stat-card-mini:hover {
        transform: translateY(-2px);
    }
    .stat-card-mini .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }
    .stat-card-mini .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0a1628;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-card-mini .stat-label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }
    .badge-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Attendance</h2>
            <p class="text-muted mb-0">Track your attendance records</p>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span style="background: #0a1628; color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        {{ $student->full_name }}
                    </span>
                    <span style="background: #e7f1ff; color: #0d6efd; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        {{ $student->schoolClass->name ?? '' }}{{ $student->section ? ' — ' . $student->section->name : '' }}
                    </span>
                </div>
                <form method="GET" action="{{ route('student.attendance.index') }}" class="d-flex align-items-center gap-2">
                    <label class="sb-form-label mb-0" style="white-space: nowrap;">Month:</label>
                    <input type="month" name="month" class="sb-form-input" style="width: auto;" value="{{ $month }}" onchange="this.form.submit()">
                </form>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card-mini">
                <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="stat-value">{{ $stats['total_days'] }}</div>
                <div class="stat-label">Total Days</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card-mini">
                <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="stat-value" style="color: #0f5132;">{{ $stats['present'] }}</div>
                <div class="stat-label">Present</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card-mini">
                <div class="stat-icon" style="background: #f8d7da; color: #842029;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
                <div class="stat-value" style="color: #842029;">{{ $stats['absent'] }}</div>
                <div class="stat-label">Absent</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card-mini">
                <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                </div>
                <div class="stat-value" style="color: #664d03;">{{ $stats['attendance_rate'] }}%</div>
                <div class="stat-label">Attendance Rate</div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="sb-section-header" style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                <h6 style="margin: 0; font-weight: 600;">Attendance Records</h6>
                <span style="color: #6c757d; font-size: 13px;">{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Class</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td style="font-weight: 500;">{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('D, M d, Y') }}</td>
                                <td>
                                    @php
                                        $statusStyles = [
                                            'present' => 'background: #d1e7dd; color: #0f5132;',
                                            'absent' => 'background: #f8d7da; color: #842029;',
                                            'late' => 'background: #fff3cd; color: #664d03;',
                                            'excused' => 'background: #cff4fc; color: #055160;',
                                        ];
                                    @endphp
                                    <span class="badge-status" style="{{ $statusStyles[$attendance->status] ?? '' }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td>{{ $attendance->schoolClass->name ?? '—' }}</td>
                                <td style="color: #6c757d;">{{ $attendance->remarks ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No attendance records found for this month.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
