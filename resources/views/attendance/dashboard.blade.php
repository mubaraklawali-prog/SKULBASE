@extends('layouts.app')

@section('title', 'Attendance Dashboard - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Attendance Dashboard</h2>
            <p class="text-muted mb-0">Overview for {{ now()->parse($today)->format('l, M d, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('attendance.create') }}" class="sb-btn sb-btn-primary">Take Attendance</a>
            <a href="{{ route('attendance.class-report') }}" class="sb-btn sb-btn-dark">Class Report</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0f5132;">{{ $totalPresentToday }}</p>
                        <p class="stat-label">Present Today</p>
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
                        <p class="stat-number" style="color: #842029;">{{ $totalAbsentToday }}</p>
                        <p class="stat-label">Absent Today</p>
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
                        <p class="stat-number" style="color: #664d03;">{{ $totalLateToday }}</p>
                        <p class="stat-label">Late Today</p>
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
                        <p class="stat-number" style="color: #0d6efd;">{{ $attendancePercentage }}%</p>
                        <p class="stat-label">Attendance Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Class Attendance Today</h5>
                    @if($classesWithTodayAttendance->isEmpty())
                        <div class="sb-empty-state">
                            <p class="text-muted" style="margin: 0;">No classes found.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="sb-table table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Class</th>
                                        <th>Students</th>
                                        <th>Marked</th>
                                        <th>Progress</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classesWithTodayAttendance as $class)
                                        @php
                                            $progress = $class->students_count > 0
                                                ? round(($class->attendances_count / $class->students_count) * 100)
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td style="font-weight: 500;">{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</td>
                                            <td>{{ $class->students_count }}</td>
                                            <td>{{ $class->attendances_count }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div style="flex: 1; height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden;">
                                                        <div style="height: 100%; width: {{ $progress }}%; background: {{ $progress == 100 ? '#198754' : ($progress > 0 ? '#ffc107' : '#e9ecef') }}; border-radius: 3px;"></div>
                                                    </div>
                                                    <span style="font-size: 12px; font-weight: 600; color: #6c757d; min-width: 35px;">{{ $progress }}%</span>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('attendance.class-report.show', $class) }}" style="color: var(--primary); font-weight: 500; text-decoration: none; font-size: 13px;">View Report</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Quick Actions</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('attendance.create') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Take Attendance
                        </a>
                        <a href="{{ route('attendance.index') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                            View All Records
                        </a>
                        <a href="{{ route('attendance.class-report') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                            Class Daily Report
                        </a>
                        <a href="{{ route('attendance.monthly-report') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Monthly Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($recentAttendances->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body">
                <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Recent Activity Today</h5>
                <div class="table-responsive">
                    <table class="sb-table table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th>Marked By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendances as $record)
                                <tr>
                                    <td style="font-weight: 500;">
                                        <a href="{{ route('attendance.student', $record->student) }}" style="color: #333; text-decoration: none;">{{ $record->student->full_name }}</a>
                                    </td>
                                    <td>{{ $record->schoolClass->name ?? '—' }}</td>
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
                                    <td>{{ $record->marker->full_name ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
