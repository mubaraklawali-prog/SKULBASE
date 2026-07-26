@extends('layouts.app')

@section('title', 'Subject Rankings - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Subject Rankings</h2>
            <p class="text-muted mb-0">Student rankings per subject</p>
        </div>
        <a href="{{ route('results.computations.dashboard') }}" class="sb-btn sb-btn-ghost">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.rankings.subject') }}" class="row g-2 align-items-end">
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
                    <label class="sb-form-label">Class *</label>
                    <select name="school_class_id" required class="sb-form-select">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('school_class_id', $selectedClass) == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="sb-form-label">Subject *</label>
                    <select name="subject_id" required class="sb-form-select">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $selectedSubject) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="sb-btn sb-btn-dark">View Rankings</button>
                </div>
            </form>
        </div>
    </div>

    @if($rankings->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">{{ $subject->name ?? '' }} — {{ $exam->name ?? '' }} ({{ $class->name ?? 'All Classes' }})</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Student</th>
                                <th>Score</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rankings as $ranking)
                                <tr>
                                    <td class="fw-bold fs-6">{{ $ranking['position'] }}</td>
                                    <td class="fw-medium">{{ $ranking['student']->full_name ?? '—' }}</td>
                                    <td class="fw-semibold">{{ $ranking['average_score'] }}%</td>
                                    <td>
                                        @if($ranking['average_score'] >= 50)
                                            <span class="sb-badge sb-badge-present">Pass</span>
                                        @else
                                            <span class="sb-badge sb-badge-absent">Fail</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($selectedExam && $selectedClass && $selectedSubject)
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p>No score entries found for this subject, exam, and class.</p>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p>Please select an exam, class, and subject above to view rankings.</p>
            </div>
        </div>
    @endif
</div>
@endsection