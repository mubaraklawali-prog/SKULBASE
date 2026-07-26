@extends('layouts.app')

@section('title', 'Score History - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Score History</h2>
            <p class="text-muted mb-0">View all score entries</p>
        </div>
        <a href="{{ route('results.scores.create') }}" class="sb-btn sb-btn-primary">+ Enter Scores</a>
    </div>

    <div class="sb-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.scores.history') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="sb-form-label">Exam</label>
                    <select name="exam_id" class="sb-form-select">
                        <option value="">All Exams</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Class</label>
                    <select name="school_class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Subject</label>
                    <select name="subject_id" class="sb-form-select">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Assessment</label>
                    <select name="assessment_type_id" class="sb-form-select">
                        <option value="">All Types</option>
                        @foreach($assessmentTypes as $type)
                            <option value="{{ $type->id }}" {{ request('assessment_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Student</label>
                    <select name="student_id" class="sb-form-select">
                        <option value="">All Students</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
                    @if(request()->hasAny(['exam_id', 'school_class_id', 'subject_id', 'assessment_type_id', 'student_id']))
                        <a href="{{ route('results.scores.history') }}" class="sb-btn sb-btn-outline-secondary">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="sb-card sb-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Exam</th>
                            <th>Assessment</th>
                            <th>Score</th>
                            <th>Teacher</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scores as $score)
                            <tr>
                                <td class="fw-medium">{{ $score->student->full_name ?? '—' }}</td>
                                <td class="text-muted">{{ $score->schoolClass->name ?? '—' }}</td>
                                <td class="text-muted">{{ $score->subject->name ?? '—' }}</td>
                                <td class="text-muted">{{ $score->exam->name ?? '—' }}</td>
                                <td class="text-muted">{{ $score->assessmentType->name ?? '—' }}</td>
                                <td>
                                    <span class="sb-badge {{ $score->score >= 70 ? 'sb-badge-present' : ($score->score >= 50 ? 'sb-badge-late' : 'sb-badge-absent') }}">{{ $score->score }}%</span>
                                </td>
                                <td class="text-muted">{{ $score->teacher->full_name ?? '—' }}</td>
                                <td class="text-muted">{{ $score->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <a href="{{ route('results.scores.show', $score) }}" class="sb-btn sb-btn-sm sb-btn-outline-info d-none d-md-inline-flex">View</a>
                                        <form method="POST" action="{{ route('results.scores.destroy', $score) }}" onsubmit="return confirm('Delete this score entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="sb-empty-state">
                                        <p>No score entries found.</p>
                                        <a href="{{ route('results.scores.create') }}" class="sb-link">Enter scores now</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($scores->hasPages())
        <div class="d-flex justify-content-center mt-3">{{ $scores->links() }}</div>
    @endif
</div>
@endsection