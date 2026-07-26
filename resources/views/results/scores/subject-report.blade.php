@extends('layouts.app')

@section('title', 'Subject Score Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Subject Score Report</h2>
            <p class="text-muted mb-0">View scores by subject</p>
        </div>
        <a href="{{ route('results.scores.dashboard') }}" class="sb-btn sb-btn-ghost">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.reports.subject') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="sb-form-label">Subject *</label>
                    <select name="subject_id" required class="sb-form-select">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $selectedSubject) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
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
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="sb-btn sb-btn-dark">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    @if($report)
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-excused">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-excused">{{ $studentAverages->count() }}</p>
                            <p class="stat-label">Students</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-present">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-present">{{ $studentAverages->count() > 0 ? number_format($studentAverages->avg('avg_score'), 1) : '0' }}%</p>
                            <p class="stat-label">Class Average</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-late">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-late">{{ $studentAverages->count() > 0 ? $studentAverages->max('avg_score') : '0' }}%</p>
                            <p class="stat-label">Highest Average</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">{{ $subject->name ?? '' }} — {{ $exam->name ?? '' }} {{ $class ? '(' . $class->name . ')' : '(All Classes)' }}</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Average Score</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentAverages as $index => $data)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $data['student']->full_name ?? '—' }}</td>
                                    <td class="fw-semibold">{{ $data['avg_score'] }}%</td>
                                    <td>
                                        @if($data['avg_score'] >= 70)
                                            <span class="sb-badge sb-badge-present">Excellent</span>
                                        @elseif($data['avg_score'] >= 50)
                                            <span class="sb-badge sb-badge-late">Good</span>
                                        @else
                                            <span class="sb-badge sb-badge-absent">Needs Improvement</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('results.scores.student-report', $data['student']) }}" class="sb-link">View Full Report</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p>Please select a subject and exam above to generate the report.</p>
            </div>
        </div>
    @endif
</div>
@endsection