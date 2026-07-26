@extends('layouts.app')

@section('title', 'Top Performers - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Top Performers</h2>
            <p class="text-muted mb-0">Highest scoring students</p>
        </div>
        <a href="{{ route('results.computations.dashboard') }}" class="sb-btn sb-btn-ghost">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.rankings.top-performers') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="sb-form-label">Exam *</label>
                    <select name="exam_id" required class="sb-form-select">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="sb-form-label">Limit</label>
                    <select name="limit" class="sb-form-select">
                        <option value="10" {{ ($limit ?? 20) == 10 ? 'selected' : '' }}>Top 10</option>
                        <option value="20" {{ ($limit ?? 20) == 20 ? 'selected' : '' }}>Top 20</option>
                        <option value="50" {{ ($limit ?? 20) == 50 ? 'selected' : '' }}>Top 50</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="sb-btn sb-btn-dark">View</button>
                </div>
            </form>
        </div>
    </div>

    @if($performers->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">{{ $exam->name ?? '' }} — Top {{ $limit }} Performers</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Average Score</th>
                                <th>Grade</th>
                                <th>Position</th>
                                <th>Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($performers as $index => $performer)
                                <tr>
                                    <td class="fw-bold fs-6 {{ $index < 3 ? '' : 'text-muted' }}">{{ $index + 1 }}</td>
                                    <td class="fw-medium">
                                        <a href="{{ route('results.computations.show', $performer) }}" class="text-decoration-none">{{ $performer->student->full_name ?? '—' }}</a>
                                    </td>
                                    <td class="text-muted">{{ $performer->schoolClass->name ?? '—' }}</td>
                                    <td class="fw-semibold">{{ number_format($performer->average_score, 1) }}%</td>
                                    <td>
                                        <span class="sb-badge sb-badge-excused">{{ $performer->overall_grade ?? '—' }}</span>
                                    </td>
                                    <td class="fw-semibold">@if($performer->class_position)@php $pos = $performer->class_position; $suffix = match(true) { $pos % 100 >= 11 && $pos % 100 <= 13 => 'th', $pos % 10 === 1 => 'st', $pos % 10 === 2 => 'nd', $pos % 10 === 3 => 'rd', default => 'th' }; @endphp{{ $pos . $suffix }}@else—@endif</td>
                                    <td class="text-muted">{{ $performer->subjects_passed }}/{{ $performer->total_subjects }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($selectedExam)
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p>No report cards found for this exam.</p>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p>Please select an exam above to view top performers.</p>
            </div>
        </div>
    @endif
</div>
@endsection