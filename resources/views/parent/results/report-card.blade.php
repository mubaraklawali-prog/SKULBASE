@extends('layouts.app')

@section('title', 'Report Card - Skulbase')

@section('content')
<style>
    .child-selector .form-check {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .child-selector .form-check:hover {
        border-color: var(--primary);
        background: #f8f9ff;
    }
    .child-selector .form-check-input:checked + .form-check-label {
        font-weight: 600;
        color: #0a1628;
    }
    .child-selector .form-check:has(.form-check-input:checked) {
        border-color: var(--primary);
        background: #f0f7ff;
    }
    .summary-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        background: #fff;
    }
    .summary-stat {
        text-align: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    .summary-stat .label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        font-weight: 600;
    }
    .summary-stat .value {
        font-size: 28px;
        font-weight: 700;
        color: #0a1628;
    }
    .summary-stat .value small {
        font-size: 14px;
        color: #6c757d;
        font-weight: 400;
    }
    .grade-badge-lg {
        display: inline-block;
        padding: 8px 24px;
        border-radius: 24px;
        font-size: 16px;
        font-weight: 700;
        background: #d1e7dd;
        color: #0f5132;
    }
    .remarks-box {
        background: #f8f9fa;
        border-left: 4px solid var(--primary);
        border-radius: 0 8px 8px 0;
        padding: 16px 20px;
        margin-top: 16px;
    }
    @media print {
        .no-print { display: none !important; }
        .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h2>Report Card</h2>
            <p class="text-muted mb-0">Detailed exam report for {{ $reportCard->student->full_name ?? '' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('parent.results.index', ['student_id' => $reportCard->student_id]) }}"
               class="sb-btn sb-btn-outline-secondary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Results
            </a>
            <button onclick="window.print()" class="sb-btn sb-btn-outline-secondary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print
            </button>
        </div>
    </div>

    <div class="summary-card mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h4 style="font-weight: 700; color: #0a1628; margin-bottom: 4px;">{{ $reportCard->exam->name ?? 'Report Card' }}</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">
                    {{ $reportCard->student->full_name ?? '' }}
                    @if($reportCard->student?->schoolClass)
                        &mdash; {{ $reportCard->student->schoolClass->name }}{{ $reportCard->student->section ? ' — ' . $reportCard->student->section->name : '' }}
                    @endif
                </p>
            </div>
            @if($reportCard->grade)
                <span class="grade-badge-lg">{{ $reportCard->grade }}</span>
            @endif
        </div>

        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="summary-stat">
                    <div class="label">Average</div>
                    <div class="value">{{ $reportCard->average ?? '—' }}<small>%</small></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-stat">
                    <div class="label">Grade</div>
                    <div class="value">{{ $reportCard->grade ?? '—' }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-stat">
                    <div class="label">Position</div>
                    <div class="value">{{ $reportCard->position ?? '—' }}<small> {{ $reportCard->position ? ordinal($reportCard->position) : '' }}</small></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-stat">
                    <div class="label">Total Students</div>
                    <div class="value">{{ $reportCard->total_students ?? '—' }}</div>
                </div>
            </div>
        </div>

        @if($reportCard->remarks)
            <div class="remarks-box">
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; color: var(--primary); margin-bottom: 6px;">Remarks</div>
                <p style="margin: 0; color: #333; font-size: 14px;">{{ $reportCard->remarks }}</p>
            </div>
        @endif
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="p-4 pb-3">
                <h5 style="font-weight: 700; color: #0a1628; margin-bottom: 4px;">Subject Scores</h5>
                <p class="text-muted mb-0" style="font-size: 13px;">Breakdown of scores by subject</p>
            </div>
            @if($groupedResults && count($groupedResults))
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 14px;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Subject</th>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Assessment Type</th>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px; text-align: center;">Score</th>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedResults as $subjectId => $subjectData)
                                <tr>
                                    <td colspan="4" style="background: #f0f7ff; font-weight: 700; color: #0a1628; font-size: 14px; border-bottom: 2px solid var(--primary); padding: 10px 16px;">
                                        {{ $subjectData['subject'] }}
                                    </td>
                                </tr>
                                @foreach($subjectData['scores'] as $score)
                                    <tr>
                                        <td style="padding: 12px 16px;">{{ $subjectData['subject'] }}</td>
                                        <td style="padding: 12px 16px;">{{ $score['assessment_type'] ?? '—' }}</td>
                                        <td style="padding: 12px 16px; text-align: center;">
                                            <span class="fw-bold" style="font-size: 15px;">{{ $score['score'] ?? '—' }}</span>
                                        </td>
                                        <td style="padding: 12px 16px; color: #6c757d; font-size: 13px;">—</td>
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
                    </svg>
                    <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">No Scores Available</h5>
                    <p style="color: #adb5bd; margin: 0;">No subject scores found for this report card.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
