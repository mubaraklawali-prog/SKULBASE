@extends('layouts.app')

@section('title', 'Performance Analytics - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Performance Analytics</h2>
            <p class="text-muted mb-0">Detailed performance analysis</p>
        </div>
        <a href="{{ route('results.computations.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.analytics') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Exam *</label>
                    <select name="exam_id" required style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Class (Optional)</label>
                    <select name="school_class_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('school_class_id', $selectedClass) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 8px 16px; font-weight: 500; font-size: 13px;">Analyze</button>
                </div>
            </form>
        </div>
    </div>

    @if($analytics)
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #0d6efd;">{{ number_format($analytics['totalStudents']) }}</p>
                            <p class="stat-label">Total Students</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #0f5132;">{{ number_format($analytics['averageScore'], 1) }}%</p>
                            <p class="stat-label">Average Score</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #0f5132;">{{ number_format($analytics['passRate'], 1) }}%</p>
                            <p class="stat-label">Pass Rate</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #664d03;">{{ $analytics['highestScore'] ? number_format($analytics['highestScore'], 1) . '%' : 'N/A' }}</p>
                            <p class="stat-label">Highest Score</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body">
                        <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Pass/Fail Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #0f5132; font-weight: 600;">Passed</span>
                            <span style="font-weight: 600;">{{ $analytics['passCount'] }} students</span>
                        </div>
                        <div style="height: 8px; background: #e9ecef; border-radius: 4px; margin-bottom: 16px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $analytics['passRate'] }}%; background: #198754; border-radius: 4px;"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #842029; font-weight: 600;">Failed</span>
                            <span style="font-weight: 600;">{{ $analytics['failCount'] }} students</span>
                        </div>
                        <div style="height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: {{ 100 - $analytics['passRate'] }}%; background: #dc3545; border-radius: 4px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body">
                        <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Grade Distribution</h6>
                        @if($analytics['gradeDistribution']->isEmpty())
                            <p class="text-muted" style="margin: 0;">No grade data available.</p>
                        @else
                            @foreach($analytics['gradeDistribution'] as $grade => $count)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span style="font-weight: 500;">{{ $grade }}</span>
                                    <span style="color: #6c757d;">{{ $count }} students</span>
                                </div>
                                <div style="height: 6px; background: #e9ecef; border-radius: 3px; margin-bottom: 8px; overflow: hidden;">
                                    <div style="height: 100%; width: {{ $analytics['totalStudents'] > 0 ? ($count / $analytics['totalStudents']) * 100 : 0 }}%; background: #4f9cf7; border-radius: 3px;"></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($analytics['classAverages']->isNotEmpty())
            <div class="card stat-card">
                <div class="card-body">
                    <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Class Averages</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Students</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['classAverages'] as $item)
                                    <tr>
                                        <td style="padding: 12px 16px; font-weight: 500;">{{ $item['class']->name ?? '—' }}{{ $item['class']->section ? ' - ' . $item['class']->section : '' }}</td>
                                        <td style="padding: 12px 16px; color: #6c757d;">{{ $item['count'] }}</td>
                                        <td style="padding: 12px 16px; font-weight: 600;">{{ $item['average'] }}%</td>
                                        <td style="padding: 12px 16px;">
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="flex: 1; height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden;">
                                                    <div style="height: 100%; width: {{ $item['average'] }}%; background: {{ $item['average'] >= 70 ? '#198754' : ($item['average'] >= 50 ? '#ffc107' : '#dc3545') }}; border-radius: 3px;"></div>
                                                </div>
                                                <span style="font-size: 12px; font-weight: 600; min-width: 35px;">{{ $item['average'] }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">Please select an exam above to view performance analytics.</p>
            </div>
        </div>
    @endif
</div>
@endsection
