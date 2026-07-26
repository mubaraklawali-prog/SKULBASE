@extends('layouts.app')

@section('title', 'Report Card Detail - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Report Card</h2>
            <p class="text-muted mb-0">{{ $reportCard->student->full_name ?? '' }} — {{ $reportCard->exam->name ?? '' }}</p>
        </div>
        <a href="javascript:window.print()" class="sb-btn sb-btn-primary">Print Report Card</a>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="fw-bold mb-0">{{ $reportCard->student->full_name ?? '' }}</h4>
                            <p class="text-muted mb-0 mt-1">Admission: {{ $reportCard->student->admission_number ?? '—' }}</p>
                        </div>
                        <div class="text-end">
                            <p class="text-muted small mb-0">Class: {{ $reportCard->schoolClass->name ?? '' }}{{ $reportCard->schoolClass->section ? ' - ' . $reportCard->schoolClass->section : '' }}</p>
                            <p class="text-muted small mb-0">Exam: {{ $reportCard->exam->name ?? '' }}</p>
                            @if($reportCard->exam->term)
                                <p class="text-muted small mb-0">Term: {{ $reportCard->exam->term }}</p>
                            @endif
                            @if($reportCard->exam->session)
                                <p class="text-muted small mb-0">Session: {{ $reportCard->exam->session }}</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-semibold mb-3">Subject Scores</h6>

                    @if($subjectScores->isEmpty())
                        <p class="text-muted">No subject scores available.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 sb-table">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjectScores as $subjectId => $data)
                                        <tr>
                                            <td class="fw-medium">{{ $data['subject']->name ?? '—' }}</td>
                                            <td class="fw-semibold">{{ $data['total_score'] }}%</td>
                                            <td>
                                                @if($data['total_score'] >= 50)
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
                    @endif

                    <hr>

                    <h6 class="fw-semibold mb-3">Comments</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Teacher Comment</label>
                            <p>{{ $reportCard->teacher_comment ?? 'No comment' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-detail-label">Principal Comment</label>
                            <p>{{ $reportCard->principal_comment ?? 'No comment' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-semibold mb-3">Edit Comments</h6>

                    <form method="POST" action="{{ route('results.computations.update-comment', $reportCard) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Teacher Comment</label>
                                <textarea name="teacher_comment" rows="3" class="sb-form-textarea">{{ old('teacher_comment', $reportCard->teacher_comment) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Principal Comment</label>
                                <textarea name="principal_comment" rows="3" class="sb-form-textarea">{{ old('principal_comment', $reportCard->principal_comment) }}</textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="sb-btn sb-btn-primary">Save Comments</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card mb-4">
                <div class="card-body p-3 text-center">
                    <label class="sb-detail-label">Position</label>
                    <p class="fw-bold mb-0" style="font-size: 48px;">@if($reportCard->class_position)@php $pos = $reportCard->class_position; $suffix = match(true) { $pos % 100 >= 11 && $pos % 100 <= 13 => 'th', $pos % 10 === 1 => 'st', $pos % 10 === 2 => 'nd', $pos % 10 === 3 => 'rd', default => 'th' }; @endphp{{ $pos . $suffix }}@else—@endif</p>
                    <p class="text-muted small mb-0">out of {{ $reportCard->total_subjects }} subjects</p>
                </div>
            </div>

            <div class="card stat-card mb-4">
                <div class="card-body p-3">
                    <h6 class="fw-semibold mb-3">Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Average Score</span>
                        <span class="fw-semibold">{{ number_format($reportCard->average_score, 1) }}%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Score</span>
                        <span class="fw-semibold">{{ number_format($reportCard->total_score, 1) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Grade</span>
                        <span class="sb-badge sb-badge-excused">{{ $reportCard->overall_grade ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Remark</span>
                        <span class="fw-medium">{{ $reportCard->overall_remark ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subjects Passed</span>
                        <span class="text-success fw-semibold">{{ $reportCard->subjects_passed }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subjects Failed</span>
                        <span class="text-danger fw-semibold">{{ $reportCard->subjects_failed }}</span>
                    </div>
                    @if($reportCard->attendance_percentage)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Attendance</span>
                            <span class="fw-semibold">{{ number_format($reportCard->attendance_percentage, 1) }}%</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card stat-card">
                <div class="card-body p-3 text-center">
                    <p class="text-muted small text-uppercase fw-semibold mb-2">Status</p>
                    @if($reportCard->status === 'published')
                        <span class="sb-badge sb-badge-present">Published</span>
                    @elseif($reportCard->status === 'approved')
                        <span class="sb-badge sb-badge-late">Approved</span>
                    @else
                        <span class="sb-badge sb-badge-secondary">Draft</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection