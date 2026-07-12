@extends('layouts.app')

@section('title', 'Report Card Detail - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Report Card</h2>
            <p class="text-muted mb-0">{{ $reportCard->student->full_name ?? '' }} — {{ $reportCard->exam->name ?? '' }}</p>
        </div>
        <a href="javascript:window.print()" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Print Report Card</a>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card stat-card">
                <div class="card-body" style="padding: 32px;">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 style="font-weight: 700; margin: 0; color: #1a1a2e;">{{ $reportCard->student->full_name ?? '' }}</h4>
                            <p style="margin: 4px 0 0; color: #6c757d;">Admission: {{ $reportCard->student->admission_number ?? '—' }}</p>
                        </div>
                        <div class="text-end">
                            <p style="margin: 0; font-size: 13px; color: #6c757d;">Class: {{ $reportCard->schoolClass->name ?? '' }}{{ $reportCard->schoolClass->section ? ' - ' . $reportCard->schoolClass->section : '' }}</p>
                            <p style="margin: 0; font-size: 13px; color: #6c757d;">Exam: {{ $reportCard->exam->name ?? '' }}</p>
                            @if($reportCard->exam->term)
                                <p style="margin: 0; font-size: 13px; color: #6c757d;">Term: {{ $reportCard->exam->term }}</p>
                            @endif
                            @if($reportCard->exam->session)
                                <p style="margin: 0; font-size: 13px; color: #6c757d;">Session: {{ $reportCard->exam->session }}</p>
                            @endif
                        </div>
                    </div>

                    <hr style="margin: 20px 0; border-color: #e9ecef;">

                    <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Subject Scores</h6>

                    @if($subjectScores->isEmpty())
                        <p class="text-muted">No subject scores available.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr style="background: #f8f9fa;">
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Subject</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Score</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjectScores as $subjectId => $data)
                                        <tr>
                                            <td style="padding: 12px 16px; font-weight: 500;">{{ $data['subject']->name ?? '—' }}</td>
                                            <td style="padding: 12px 16px; font-weight: 600;">{{ $data['total_score'] }}%</td>
                                            <td style="padding: 12px 16px;">
                                                @if($data['total_score'] >= 50)
                                                    <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Pass</span>
                                                @else
                                                    <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Fail</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <hr style="margin: 20px 0; border-color: #e9ecef;">

                    <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Comments</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Teacher Comment</label>
                            <p style="margin: 0; font-size: 14px;">{{ $reportCard->teacher_comment ?? 'No comment' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Principal Comment</label>
                            <p style="margin: 0; font-size: 14px;">{{ $reportCard->principal_comment ?? 'No comment' }}</p>
                        </div>
                    </div>

                    <hr style="margin: 20px 0; border-color: #e9ecef;">

                    <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Edit Comments</h6>

                    <form method="POST" action="{{ route('results.computations.update-comment', $reportCard) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Teacher Comment</label>
                                <textarea name="teacher_comment" rows="3" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">{{ old('teacher_comment', $reportCard->teacher_comment) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Principal Comment</label>
                                <textarea name="principal_comment" rows="3" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">{{ old('principal_comment', $reportCard->principal_comment) }}</textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">Save Comments</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card mb-4">
                <div class="card-body" style="padding: 24px; text-align: center;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Position</label>
                    <p style="margin: 0; font-size: 48px; font-weight: 700; color: #1a1a2e;">{{ $reportCard->class_position ? $this->ordinal($reportCard->class_position) : '—' }}</p>
                    <p style="margin: 4px 0 0; color: #6c757d; font-size: 14px;">out of {{ $reportCard->total_subjects }} subjects</p>
                </div>
            </div>

            <div class="card stat-card mb-4">
                <div class="card-body" style="padding: 24px;">
                    <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color: #6c757d; font-size: 14px;">Average Score</span>
                        <span style="font-weight: 600; font-size: 14px;">{{ number_format($reportCard->average_score, 1) }}%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color: #6c757d; font-size: 14px;">Total Score</span>
                        <span style="font-weight: 600; font-size: 14px;">{{ number_format($reportCard->total_score, 1) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color: #6c757d; font-size: 14px;">Grade</span>
                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $reportCard->overall_grade ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color: #6c757d; font-size: 14px;">Remark</span>
                        <span style="font-weight: 500; font-size: 14px;">{{ $reportCard->overall_remark ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color: #6c757d; font-size: 14px;">Subjects Passed</span>
                        <span style="color: #0f5132; font-weight: 600; font-size: 14px;">{{ $reportCard->subjects_passed }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color: #6c757d; font-size: 14px;">Subjects Failed</span>
                        <span style="color: #842029; font-weight: 600; font-size: 14px;">{{ $reportCard->subjects_failed }}</span>
                    </div>
                    @if($reportCard->attendance_percentage)
                        <div class="d-flex justify-content-between">
                            <span style="color: #6c757d; font-size: 14px;">Attendance</span>
                            <span style="font-weight: 600; font-size: 14px;">{{ number_format($reportCard->attendance_percentage, 1) }}%</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card stat-card">
                <div class="card-body" style="padding: 24px; text-align: center;">
                    <p style="margin: 0; font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                    @if($reportCard->status === 'published')
                        <span style="background: #d1e7dd; color: #0f5132; padding: 6px 20px; border-radius: 20px; font-size: 14px; font-weight: 600;">Published</span>
                    @elseif($reportCard->status === 'approved')
                        <span style="background: #fff3cd; color: #664d03; padding: 6px 20px; border-radius: 20px; font-size: 14px; font-weight: 600;">Approved</span>
                    @else
                        <span style="background: #f0f2f5; color: #6c757d; padding: 6px 20px; border-radius: 20px; font-size: 14px; font-weight: 600;">Draft</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
