@extends('layouts.app')

@section('title', 'Monthly Attendance Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Monthly Attendance Report</h2>
            <p class="text-muted mb-0">Monthly attendance summary for a class</p>
        </div>
        <a href="{{ route('attendance.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            Back to Dashboard
        </a>
    </div>

    <form method="GET" action="{{ route('attendance.monthly-report') }}" class="card stat-card mb-4">
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
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Month</label>
                    <input type="month" name="month" value="{{ $selectedMonth }}" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
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
            $monthName = now()->parse($selectedMonth)->format('F Y');
        @endphp
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center" style="padding: 20px;">
                        <p class="stat-number" style="font-size: 28px; color: #1a1a2e;">{{ $r['totalSchoolDays'] }}</p>
                        <p class="stat-label">School Days</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center" style="padding: 20px;">
                        <p class="stat-number" style="font-size: 28px; color: #0f5132;">{{ $r['totalPresentAll'] }}</p>
                        <p class="stat-label">Total Present (All Students)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center" style="padding: 20px;">
                        <p class="stat-number" style="font-size: 28px; color: #842029;">{{ $r['totalAbsencesAll'] }}</p>
                        <p class="stat-label">Total Absences</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center" style="padding: 20px;">
                        <p class="stat-number" style="font-size: 28px; color: {{ $r['overallPercentage'] >= 75 ? '#0f5132' : '#842029' }};">{{ $r['overallPercentage'] }}%</p>
                        <p class="stat-label">Overall Attendance</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-body p-0">
                <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
                    <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">
                        {{ $r['class']->name }}{{ $r['class']->section ? ' - ' . $r['class']->section : '' }}
                        &middot; {{ $monthName }}
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Days</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Present</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Absent</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Late</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Excused</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Rate</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($r['studentStats'] as $index => $stat)
                                <tr>
                                    <td style="padding: 14px 20px; color: #6c757d;">{{ $index + 1 }}</td>
                                    <td style="padding: 14px 20px; font-weight: 500;">{{ $stat['student']->full_name }}</td>
                                    <td style="padding: 14px 20px; text-align: center; color: #6c757d;">{{ $stat['total'] }}</td>
                                    <td style="padding: 14px 20px; text-align: center;">
                                        <span style="color: #0f5132; font-weight: 600;">{{ $stat['present'] }}</span>
                                    </td>
                                    <td style="padding: 14px 20px; text-align: center;">
                                        <span style="color: #842029; font-weight: 600;">{{ $stat['absent'] }}</span>
                                    </td>
                                    <td style="padding: 14px 20px; text-align: center;">
                                        <span style="color: #664d03; font-weight: 600;">{{ $stat['late'] }}</span>
                                    </td>
                                    <td style="padding: 14px 20px; text-align: center;">
                                        <span style="color: #0d6efd; font-weight: 600;">{{ $stat['excused'] }}</span>
                                    </td>
                                    <td style="padding: 14px 20px; text-align: center;">
                                        @if($stat['percentage'] >= 75)
                                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $stat['percentage'] }}%</span>
                                        @elseif($stat['percentage'] >= 50)
                                            <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $stat['percentage'] }}%</span>
                                        @else
                                            <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $stat['percentage'] }}%</span>
                                        @endif
                                    </td>
                                    <td style="padding: 14px 20px; text-align: right;">
                                        <a href="{{ route('attendance.student', $stat['student']) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                        <p style="margin: 0; font-size: 15px;">No attendance data found for this month.</p>
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
