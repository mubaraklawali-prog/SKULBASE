@extends('layouts.app')

@section('title', 'Score Detail - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Score Detail</h2>
            <p class="text-muted mb-0">{{ $score->student->full_name ?? '' }} — {{ $score->subject->name ?? '' }}</p>
        </div>
        <a href="{{ route('results.scores.history') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to History</a>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card stat-card">
                <div class="card-body" style="padding: 32px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Score Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Student</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->student->full_name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Admission No</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->student->admission_number ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Class</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->schoolClass->name ?? '—' }}{{ $score->schoolClass->section ? ' - ' . $score->schoolClass->section : '' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Subject</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->subject->name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Exam</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->exam->name ?? '—' }} {{ $score->exam->term ? '(' . $score->exam->term . ')' : '' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Assessment Type</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->assessmentType->name ?? '—' }} ({{ $score->assessmentType->percentage ?? 0 }}%)</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Teacher</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->teacher->full_name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Date Entered</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Last Updated</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @if($score->remarks)
                        <div class="mt-2">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Remarks</label>
                            <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $score->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card">
                <div class="card-body" style="padding: 32px; text-align: center;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Score</label>
                    <p style="margin: 0; font-size: 48px; font-weight: 700; color: {{ $score->score >= 70 ? '#0f5132' : ($score->score >= 50 ? '#664d03' : '#842029') }};">{{ $score->score }}%</p>
                    <div class="mt-3">
                        <a href="{{ route('results.scores.student-report', $score->student) }}" class="btn" style="background: #e7f1ff; color: #0d6efd; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none; display: block; margin-bottom: 8px;">View Student Report</a>
                        <form method="POST" action="{{ route('results.scores.destroy', $score) }}" onsubmit="return confirm('Delete this score entry?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: #f8d7da; color: #842029; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer; width: 100%;">Delete Score</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
