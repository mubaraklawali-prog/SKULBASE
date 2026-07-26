@extends('layouts.app')

@section('title', 'Class Daily Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Class Daily Report</h2>
            <p class="text-muted mb-0">View attendance for a specific class and date</p>
        </div>
        <a href="{{ route('attendance.dashboard') }}" class="sb-btn sb-btn-ghost">
            Back to Dashboard
        </a>
    </div>

    <form method="GET" action="{{ route('attendance.class-report') }}" class="card stat-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-sm">
                    <label class="sb-form-label">Select Class</label>
                    <select name="class_id" required class="sb-form-select">
                        <option value="">-- Choose a class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm">
                    <label class="sb-form-label">Date</label>
                    <input type="date" name="date" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}" required class="sb-form-input">
                </div>
                <div class="col-12 col-sm-auto">
                    <button type="submit" class="sb-btn sb-btn-dark w-100">
                        View Report
                    </button>
                </div>
            </div>
        </div>
    </form>

    @if($report)
        @php
            $r = $report;
        @endphp
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-present">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-present">{{ $r['presentCount'] }}</p>
                            <p class="stat-label">Present</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-absent">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-absent">{{ $r['absentCount'] }}</p>
                            <p class="stat-label">Absent</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-late">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-late">{{ $r['lateCount'] }}</p>
                            <p class="stat-label">Late</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-excused">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-excused">{{ $r['excusedCount'] }}</p>
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
                        <h5 class="mb-0 fw-semibold">{{ $r['class']->name }}{{ $r['class']->section ? ' - ' . $r['class']->section : '' }}</h5>
                        <p class="text-muted small mb-0 mt-1">
                            {{ now()->parse($selectedDate)->format('l, M d, Y') }} &middot;
                            {{ $r['markedCount'] }}/{{ $r['totalStudents'] }} students marked &middot;
                            {{ $r['attendancePercentage'] }}% attendance rate
                        </p>
                    </div>
                    <a href="{{ route('attendance.create', ['class_id' => $selectedClass, 'date' => $selectedDate]) }}" class="sb-btn sb-btn-primary">
                        {{ $r['markedCount'] > 0 ? 'Update Attendance' : 'Take Attendance' }}
                    </a>
                </div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Adm. No.</th>
                                <th>Status</th>
                                <th>History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($r['students'] as $index => $student)
                                @php $status = $r['attendanceMap'][$student->id] ?? null; @endphp
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $student->full_name }}</td>
                                    <td>
                                        <code class="sb-code">{{ $student->admission_number }}</code>
                                    </td>
                                    <td>
                                        @if($status === 'present')
                                            <span class="sb-badge sb-badge-present">Present</span>
                                        @elseif($status === 'absent')
                                            <span class="sb-badge sb-badge-absent">Absent</span>
                                        @elseif($status === 'late')
                                            <span class="sb-badge sb-badge-late">Late</span>
                                        @elseif($status === 'excused')
                                            <span class="sb-badge sb-badge-excused">Excused</span>
                                        @else
                                            <span class="sb-badge sb-badge-secondary">Not Marked</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('attendance.student', $student) }}" class="sb-link">View History</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="sb-empty-state">
                                            <p>No students found in this class.</p>
                                        </div>
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