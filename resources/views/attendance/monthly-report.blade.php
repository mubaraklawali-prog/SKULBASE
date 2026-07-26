@extends('layouts.app')

@section('title', 'Monthly Attendance Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Monthly Attendance Report</h2>
            <p class="text-muted mb-0">Monthly attendance summary for a class</p>
        </div>
        <a href="{{ route('attendance.dashboard') }}" class="sb-btn sb-btn-ghost">
            Back to Dashboard
        </a>
    </div>

    <form method="GET" action="{{ route('attendance.monthly-report') }}" class="card stat-card mb-4">
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
                    <label class="sb-form-label">Month</label>
                    <input type="month" name="month" value="{{ $selectedMonth }}" required class="sb-form-input">
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
            $monthName = now()->parse($selectedMonth)->format('F Y');
        @endphp
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body text-center p-4">
                        <p class="stat-number sb-stat-number">{{ $r['totalSchoolDays'] }}</p>
                        <p class="stat-label">School Days</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body text-center p-4">
                        <p class="stat-number sb-stat-number sb-stat-number-present">{{ $r['totalPresentAll'] }}</p>
                        <p class="stat-label">Total Present (All Students)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body text-center p-4">
                        <p class="stat-number sb-stat-number sb-stat-number-absent">{{ $r['totalAbsencesAll'] }}</p>
                        <p class="stat-label">Total Absences</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body text-center p-4">
                        <p class="stat-number sb-stat-number {{ $r['overallPercentage'] >= 75 ? 'sb-stat-number-present' : 'sb-stat-number-absent' }}">{{ $r['overallPercentage'] }}%</p>
                        <p class="stat-label">Overall Attendance</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-body p-0">
                <div class="sb-section-header">
                    <h5>
                        {{ $r['class']->name }}{{ $r['class']->section ? ' - ' . $r['class']->section : '' }}
                        &middot; {{ $monthName }}
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Present</th>
                                <th class="text-center">Absent</th>
                                <th class="text-center">Late</th>
                                <th class="text-center">Excused</th>
                                <th class="text-center">Rate</th>
                                <th class="text-end">History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($r['studentStats'] as $index => $stat)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $stat['student']->full_name }}</td>
                                    <td class="text-center text-muted">{{ $stat['total'] }}</td>
                                    <td class="text-center">
                                        <span class="text-success fw-semibold">{{ $stat['present'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-danger fw-semibold">{{ $stat['absent'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-warning fw-semibold">{{ $stat['late'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-primary fw-semibold">{{ $stat['excused'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($stat['percentage'] >= 75)
                                            <span class="sb-badge sb-badge-present">{{ $stat['percentage'] }}%</span>
                                        @elseif($stat['percentage'] >= 50)
                                            <span class="sb-badge sb-badge-late">{{ $stat['percentage'] }}%</span>
                                        @else
                                            <span class="sb-badge sb-badge-absent">{{ $stat['percentage'] }}%</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('attendance.student', $stat['student']) }}" class="sb-link">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="sb-empty-state">
                                            <p>No attendance data found for this month.</p>
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