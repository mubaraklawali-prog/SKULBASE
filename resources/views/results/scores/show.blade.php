@extends('layouts.app')

@section('title', 'Score Detail - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Score Detail</h2>
            <p class="text-muted mb-0">{{ $score->student->full_name ?? '' }} — {{ $score->subject->name ?? '' }}</p>
        </div>
        <a href="{{ route('results.scores.history') }}" class="sb-btn sb-btn-ghost">Back to History</a>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card stat-card">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3">Score Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Student</label>
                            <p class="fw-medium mb-0">{{ $score->student->full_name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Admission No</label>
                            <p class="fw-medium mb-0">{{ $score->student->admission_number ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Class</label>
                            <p class="fw-medium mb-0">{{ $score->schoolClass->name ?? '—' }}{{ $score->schoolClass->section ? ' - ' . $score->schoolClass->section : '' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Subject</label>
                            <p class="fw-medium mb-0">{{ $score->subject->name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Exam</label>
                            <p class="fw-medium mb-0">{{ $score->exam->name ?? '—' }} {{ $score->exam->term ? '(' . $score->exam->term . ')' : '' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Assessment Type</label>
                            <p class="fw-medium mb-0">{{ $score->assessmentType->name ?? '—' }} ({{ $score->assessmentType->percentage ?? 0 }}%)</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Teacher</label>
                            <p class="fw-medium mb-0">{{ $score->teacher->full_name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Date Entered</label>
                            <p class="fw-medium mb-0">{{ $score->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Last Updated</label>
                            <p class="fw-medium mb-0">{{ $score->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @if($score->remarks)
                        <div class="mt-2">
                            <label class="sb-detail-label">Remarks</label>
                            <p class="fw-medium mb-0">{{ $score->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card">
                <div class="card-body p-4 text-center">
                    <label class="sb-detail-label">Score</label>
                    <p class="{{ $score->score >= 70 ? 'sb-stat-number-present' : ($score->score >= 50 ? 'sb-stat-number-late' : 'sb-stat-number-absent') }}" style="font-size: 48px; font-weight: 700;">{{ $score->score }}%</p>
                    <div class="mt-3">
                        <a href="{{ route('results.scores.student-report', $score->student) }}" class="sb-btn sb-btn-outline-info d-block mb-2">View Student Report</a>
                        <form method="POST" action="{{ route('results.scores.destroy', $score) }}" onsubmit="return confirm('Delete this score entry?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="sb-btn sb-btn-outline-danger w-100">Delete Score</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection