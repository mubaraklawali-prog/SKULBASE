@extends('layouts.app')

@section('title', 'Class Rankings - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Class Rankings</h2>
            <p class="text-muted mb-0">Student positions and rankings</p>
        </div>
        <a href="{{ route('results.computations.dashboard') }}" class="sb-btn sb-btn-ghost">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.rankings.class') }}" class="row g-2 align-items-end">
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
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="sb-btn sb-btn-dark">View Rankings</button>
                </div>
            </form>
        </div>
    </div>

    @if($rankings->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">{{ $exam->name ?? '' }} — {{ $class->name ?? '' }} Rankings</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Student</th>
                                <th>Average Score</th>
                                <th>Grade</th>
                                <th>Remark</th>
                                <th>Subjects Passed</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rankings as $card)
                                <tr>
                                    <td class="fw-bold fs-6 {{ $card->class_position <= 3 ? '' : 'text-muted' }}">@if($card->class_position)@php $pos = $card->class_position; $suffix = match(true) { $pos % 100 >= 11 && $pos % 100 <= 13 => 'th', $pos % 10 === 1 => 'st', $pos % 10 === 2 => 'nd', $pos % 10 === 3 => 'rd', default => 'th' }; @endphp{{ $pos . $suffix }}@else—@endif</td>
                                    <td class="fw-medium">
                                        <a href="{{ route('results.computations.show', $card) }}" class="text-decoration-none">{{ $card->student->full_name ?? '—' }}</a>
                                    </td>
                                    <td class="fw-semibold">{{ number_format($card->average_score, 1) }}%</td>
                                    <td>
                                        <span class="sb-badge sb-badge-excused">{{ $card->overall_grade ?? '—' }}</span>
                                    </td>
                                    <td class="text-muted">{{ $card->overall_remark ?? '—' }}</td>
                                    <td class="text-success fw-semibold">{{ $card->subjects_passed }}/{{ $card->total_subjects }}</td>
                                    <td class="text-muted">{{ $card->attendance_percentage ? number_format($card->attendance_percentage, 1) . '%' : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($selectedExam && $selectedClass)
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p>No report cards found for this exam and class.</p>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p>Please select an exam and class above to view rankings.</p>
            </div>
        </div>
    @endif
</div>
@endsection