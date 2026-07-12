@extends('layouts.app')

@section('title', 'Class Daily Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Class Daily Report</h2>
            <p class="text-muted mb-0">View attendance for a specific class and date</p>
        </div>
        <a href="{{ route('attendance.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            Back to Dashboard
        </a>
    </div>

    <form method="GET" action="{{ route('attendance.class-report') }}" class="card stat-card mb-4">
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Select Class</label>
                    <select name="class_id" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                        <option value="">-- Choose a class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Date</label>
                    <input type="date" name="date" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                </div>
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">
                    View Report
                </button>
            </div>
        </div>
    </form>

    @if($report)
        @php
            $r = $report;
        @endphp
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #0f5132;">{{ $r['presentCount'] }}</p>
                            <p class="stat-label">Present</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #f8d7da; color: #842029;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #842029;">{{ $r['absentCount'] }}</p>
                            <p class="stat-label">Absent</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #664d03;">{{ $r['lateCount'] }}</p>
                            <p class="stat-label">Late</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #0d6efd;">{{ $r['excusedCount'] }}</p>
                            <p class="stat-label">Excused</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">{{ $r['class']->name }}{{ $r['class']->section ? ' - ' . $r['class']->section : '' }}</h5>
                        <p style="margin: 4px 0 0 0; font-size: 13px; color: #6c757d;">
                            {{ now()->parse($selectedDate)->format('l, M d, Y') }} &middot;
                            {{ $r['markedCount'] }}/{{ $r['totalStudents'] }} students marked &middot;
                            {{ $r['attendancePercentage'] }}% attendance rate
                        </p>
                    </div>
                    <a href="{{ route('attendance.create', ['class_id' => $selectedClass, 'date' => $selectedDate]) }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                        {{ $r['markedCount'] > 0 ? 'Update Attendance' : 'Take Attendance' }}
                    </a>
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
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($r['students'] as $index => $student)
                                @php $status = $r['attendanceMap'][$student->id] ?? null; @endphp
                                <tr>
                                    <td style="padding: 14px 20px; color: #6c757d;">{{ $index + 1 }}</td>
                                    <td style="padding: 14px 20px; font-weight: 500;">{{ $student->full_name }}</td>
                                    <td style="padding: 14px 20px;">
                                        <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $student->admission_number }}</code>
                                    </td>
                                    <td style="padding: 14px 20px;">
                                        @if($status === 'present')
                                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Present</span>
                                        @elseif($status === 'absent')
                                            <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Absent</span>
                                        @elseif($status === 'late')
                                            <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Late</span>
                                        @elseif($status === 'excused')
                                            <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Excused</span>
                                        @else
                                            <span style="background: #f0f2f5; color: #6c757d; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Not Marked</span>
                                        @endif
                                    </td>
                                    <td style="padding: 14px 20px;">
                                        <a href="{{ route('attendance.student', $student) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">View History</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                        <p style="margin: 0; font-size: 15px;">No students found in this class.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
