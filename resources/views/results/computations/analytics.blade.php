@extends('layouts.app')

@section('title', 'Performance Analytics - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Performance Analytics</h2>
            <p class="text-muted mb-0">Detailed performance analysis</p>
        </div>
        <a href="{{ route('results.computations.dashboard') }}" class="sb-btn sb-btn-ghost">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.analytics') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="sb-form-label">Exam *</label>
                    <select name="exam_id" required class="sb-form-select">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="sb-form-label">Class (Optional)</label>
                    <select name="school_class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('school_class_id', $selectedClass) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="sb-btn sb-btn-dark">Analyze</button>
                </div>
            </form>
        </div>
    </div>

    @if($analytics)
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-excused">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-excused">{{ number_format($analytics['totalStudents']) }}</p>
                            <p class="stat-label">Total Students</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-present">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-present">{{ number_format($analytics['averageScore'], 1) }}%</p>
                            <p class="stat-label">Average Score</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-present">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-present">{{ number_format($analytics['passRate'], 1) }}%</p>
                            <p class="stat-label">Pass Rate</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-late">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-late">{{ $analytics['highestScore'] ? number_format($analytics['highestScore'], 1) . '%' : 'N/A' }}</p>
                            <p class="stat-label">Highest Score</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Pass/Fail Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success fw-semibold">Passed</span>
                            <span class="fw-semibold">{{ $analytics['passCount'] }} students</span>
                        </div>
                        <div style="height: 8px; background: #e9ecef; border-radius: 4px; margin-bottom: 16px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $analytics['passRate'] }}%; background: #198754; border-radius: 4px;"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-danger fw-semibold">Failed</span>
                            <span class="fw-semibold">{{ $analytics['failCount'] }} students</span>
                        </div>
                        <div style="height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: {{ 100 - $analytics['passRate'] }}%; background: #dc3545; border-radius: 4px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Grade Distribution</h6>
                        @if($analytics['gradeDistribution']->isEmpty())
                            <p class="text-muted mb-0">No grade data available.</p>
                        @else
                            @foreach($analytics['gradeDistribution'] as $grade => $count)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-medium">{{ $grade }}</span>
                                    <span class="text-muted">{{ $count }} students</span>
                                </div>
                                <div style="height: 6px; background: #e9ecef; border-radius: 3px; margin-bottom: 8px; overflow: hidden;">
                                    <div style="height: 100%; width: {{ $analytics['totalStudents'] > 0 ? ($count / $analytics['totalStudents']) * 100 : 0 }}%; background: var(--primary); border-radius: 3px;"></div>
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
                    <h6 class="fw-semibold mb-3">Class Averages</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 sb-table">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Students</th>
                                    <th>Average</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['classAverages'] as $item)
                                    <tr>
                                        <td class="fw-medium">{{ $item['class']->name ?? '—' }}{{ $item['class']->section ? ' - ' . $item['class']->section : '' }}</td>
                                        <td class="text-muted">{{ $item['count'] }}</td>
                                        <td class="fw-semibold">{{ $item['average'] }}%</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="flex: 1; height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden;">
                                                    <div style="height: 100%; width: {{ $item['average'] }}%; background: {{ $item['average'] >= 70 ? '#198754' : ($item['average'] >= 50 ? '#ffc107' : '#dc3545') }}; border-radius: 3px;"></div>
                                                </div>
                                                <span class="small fw-semibold" style="min-width: 35px;">{{ $item['average'] }}%</span>
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
            <div class="card-body sb-empty-state">
                <p>Please select an exam above to view performance analytics.</p>
            </div>
        </div>
    @endif
</div>
@endsection