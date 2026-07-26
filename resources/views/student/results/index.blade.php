@extends('layouts.app')

@section('title', 'My Results - Skulbase')

@section('content')
<style>
    .exam-group-header {
        background: #f0f7ff;
        font-weight: 700;
        color: #0a1628;
        font-size: 14px;
        border-bottom: 2px solid var(--primary);
    }
    .report-card-item {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        transition: box-shadow 0.2s;
    }
    .report-card-item:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }
    .grade-badge {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .status-active { background: #d1e7dd; color: #0f5132; }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Results</h2>
            <p class="text-muted mb-0">View your scores, grades, and report cards</p>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span style="background: #0a1628; color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        {{ $student->full_name }}
                    </span>
                    <span style="background: #e7f1ff; color: #0d6efd; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        {{ $student->schoolClass->name ?? '' }}{{ $student->section ? ' — ' . $student->section->name : '' }}
                    </span>
                </div>
                <div>
                    <form method="GET" action="{{ route('student.results.index') }}" class="d-inline-flex align-items-center gap-2">
                        <label for="exam_filter" class="fw-semibold text-muted" style="font-size: 13px;">Exam:</label>
                        <select name="exam_id" id="exam_filter" class="form-select form-select-sm" style="width: auto; min-width: 200px;" onchange="this.form.submit()">
                            <option value="">All Exams</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card stat-card mb-4">
                <div class="card-body p-0">
                    <div class="p-4 pb-3">
                        <h5 style="font-weight: 700; color: #0a1628; margin-bottom: 4px;">Subject Scores</h5>
                        <p class="text-muted mb-0" style="font-size: 13px;">Detailed scores per subject</p>
                    </div>
                    @if($results && $results->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 14px;">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Subject</th>
                                        <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Assessment Type</th>
                                        <th style="font-weight: 600; color: #495057; padding: 12px 16px; text-align: center;">Score</th>
                                        <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Exam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grouped = $results->groupBy(fn($r) => $r->exam?->name ?? 'Unassigned');
                                    @endphp
                                    @foreach($grouped as $examName => $examResults)
                                        <tr>
                                            <td colspan="4" class="exam-group-header px-3 py-2">{{ $examName }}</td>
                                        </tr>
                                        @foreach($examResults as $result)
                                            <tr>
                                                <td style="padding: 12px 16px; font-weight: 500;">{{ $result->subject->name ?? '—' }}</td>
                                                <td style="padding: 12px 16px;">{{ $result->assessmentType->name ?? '—' }}</td>
                                                <td style="padding: 12px 16px; text-align: center;">
                                                    <span class="fw-bold" style="font-size: 15px;">{{ $result->score ?? '—' }}</span>
                                                </td>
                                                <td style="padding: 12px 16px; font-size: 13px; color: #6c757d;">{{ $result->exam->name ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="padding: 48px 20px; text-align: center;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">No Scores Available</h5>
                            <p style="color: #adb5bd; margin: 0;">No subject scores have been recorded yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 style="font-weight: 700; color: #0a1628; margin-bottom: 4px;">Report Cards</h5>
                    <p class="text-muted mb-3" style="font-size: 13px;">Exam summaries and overall grades</p>

                    @if($reportCards && $reportCards->count())
                        <div class="d-flex flex-column gap-3">
                            @foreach($reportCards as $card)
                                <div class="report-card-item">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 style="font-weight: 700; color: #0a1628; margin-bottom: 2px;">{{ $card->exam->name ?? '—' }}</h6>
                                            <small class="text-muted">{{ $student->full_name }}</small>
                                        </div>
                                        <span class="grade-badge status-active">{{ $card->overall_grade ?? '—' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <div style="font-size: 12px; color: #6c757d; margin-bottom: 2px;">Average</div>
                                            <div style="font-size: 22px; font-weight: 700; color: #0a1628;">{{ $card->average_score ?? '—' }}<span style="font-size: 13px; color: #6c757d; font-weight: 400;">%</span></div>
                                        </div>
                                        <a href="{{ route('student.results.report-card', ['reportCard' => $card->id]) }}"
                                           class="sb-btn sb-btn-outline-primary d-inline-flex align-items-center gap-2"
                                           style="font-size: 13px; padding: 8px 16px;">
                                            View Details
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 30px 20px; color: #6c757d;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            <p style="margin: 0;">No report cards available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
