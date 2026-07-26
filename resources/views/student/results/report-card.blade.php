@extends('layouts.app')

@section('title', 'Report Card - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Report Card</h2>
            <p class="text-muted mb-0">{{ $reportCard->exam->name ?? '—' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('student.results.index') }}" class="sb-btn sb-btn-secondary">
                Back to Results
            </a>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 24px;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h4 style="font-weight: 700; margin-bottom: 4px;">{{ $student->full_name }}</h4>
                    <p style="color: #6c757d; margin: 0;">{{ $student->schoolClass->name ?? '' }}{{ $student->section ? ' — ' . $student->section->name : '' }}</p>
                    <p style="color: #6c757d; font-size: 13px; margin: 4px 0 0;">Admission No: {{ $student->admission_number }}</p>
                </div>
                <div style="text-align: right;">
                    <h5 style="font-weight: 700; margin-bottom: 4px;">{{ $reportCard->exam->name ?? '—' }}</h5>
                    @if($reportCard->overall_grade)
                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                            Grade: {{ $reportCard->overall_grade }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card" style="text-align: center;">
                <div class="card-body">
                    <p style="font-size: 28px; font-weight: 700; color: #1a73e8; margin: 0;">{{ $reportCard->average_score ?? '—' }}%</p>
                    <p style="font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin: 4px 0 0 0;">Average</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card" style="text-align: center;">
                <div class="card-body">
                    <p style="font-size: 28px; font-weight: 700; color: #1e8a3e; margin: 0;">{{ $reportCard->total_score ?? '—' }}</p>
                    <p style="font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin: 4px 0 0 0;">Total Score</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card" style="text-align: center;">
                <div class="card-body">
                    <p style="font-size: 28px; font-weight: 700; color: #e67e22; margin: 0;">{{ $reportCard->class_position ? $reportCard->class_position . ordinal() : '—' }}</p>
                    <p style="font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin: 4px 0 0 0;">Position</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card" style="text-align: center;">
                <div class="card-body">
                    <p style="font-size: 28px; font-weight: 700; color: #6f42c1; margin: 0;">{{ $reportCard->total_subjects ?? '—' }}</p>
                    <p style="font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin: 4px 0 0 0;">Subjects</p>
                </div>
            </div>
        </div>
    </div>

    @if($reportCard->teacher_comment || $reportCard->principal_comment)
        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 24px;">
                <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Remarks</h5>
                @if($reportCard->teacher_comment)
                    <div style="margin-bottom: 12px;">
                        <p style="font-size: 12px; color: #6c757d; margin: 0; text-transform: uppercase;">Teacher's Comment</p>
                        <p style="margin: 4px 0 0;">{{ $reportCard->teacher_comment }}</p>
                    </div>
                @endif
                @if($reportCard->principal_comment)
                    <div>
                        <p style="font-size: 12px; color: #6c757d; margin: 0; text-transform: uppercase;">Principal's Comment</p>
                        <p style="margin: 4px 0 0;">{{ $reportCard->principal_comment }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="p-4 pb-3">
                <h5 style="font-weight: 700; color: #0a1628; margin-bottom: 4px;">Subject Breakdown</h5>
            </div>
            @if($groupedResults->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 14px;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Subject</th>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Assessment</th>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px; text-align: center;">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedResults as $subjectData)
                                @php $first = true; @endphp
                                @foreach($subjectData['scores'] as $score)
                                    <tr>
                                        @if($first)
                                            <td rowspan="{{ $subjectData['scores']->count() }}" style="padding: 12px 16px; font-weight: 600; vertical-align: middle;">{{ $subjectData['subject'] }}</td>
                                            @php $first = false; @endphp
                                        @endif
                                        <td style="padding: 12px 16px;">{{ $score['assessment_type'] }}</td>
                                        <td style="padding: 12px 16px; text-align: center;">
                                            <span class="fw-bold" style="font-size: 15px;">{{ $score['score'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style="background: #f8f9fa;">
                                    <td colspan="2" style="padding: 8px 16px; font-weight: 700;">Total — {{ $subjectData['subject'] }}</td>
                                    <td style="padding: 8px 16px; text-align: center; font-weight: 700;">{{ $subjectData['total'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding: 48px 20px; text-align: center; color: #6c757d;">
                    <p style="margin: 0;">No subject results available for this report card.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
