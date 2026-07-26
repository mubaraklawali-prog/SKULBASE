@extends('layouts.app')

@section('title', 'Score History - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Score History</h2>
            <p class="text-muted mb-0">View all your score entries</p>
        </div>
        <a href="{{ route('teacher.scores.create') }}" class="sb-btn sb-btn-primary">+ Enter Scores</a>
    </div>

    <div class="sb-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.scores.history') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="sb-form-label">Exam</label>
                    <select name="exam_id" class="sb-form-select">
                        <option value="">All Exams</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="sb-form-label">Class</label>
                    <select name="school_class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="sb-form-label">Subject</label>
                    <select name="subject_id" class="sb-form-select">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="sb-btn sb-btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="sb-card sb-table-card">
        <div class="card-body">
            @if($scores->isEmpty())
                <div class="sb-empty-state">
                    <p class="mb-0">No score entries found.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="sb-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Exam</th>
                                <th>Score</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scores as $score)
                                <tr>
                                    <td class="fw-medium">{{ $score->student->full_name ?? 'N/A' }}</td>
                                    <td>{{ $score->schoolClass->name ?? 'N/A' }}</td>
                                    <td>{{ $score->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $score->exam->name ?? 'N/A' }}</td>
                                    <td><span class="fw-semibold">{{ $score->score }}%</span></td>
                                    <td class="text-muted">{{ $score->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $scores->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
