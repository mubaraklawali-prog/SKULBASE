@extends('layouts.app')

@section('title', 'Subject Rankings - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Subject Rankings</h2>
            <p class="text-muted mb-0">Student rankings per subject</p>
        </div>
        <a href="{{ route('results.computations.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.rankings.subject') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Exam *</label>
                    <select name="exam_id" required style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Class *</label>
                    <select name="school_class_id" required style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('school_class_id', $selectedClass) == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Subject *</label>
                    <select name="subject_id" required style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $selectedSubject) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 8px 16px; font-weight: 500; font-size: 13px;">View Rankings</button>
                </div>
            </form>
        </div>
    </div>

    @if($rankings->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body">
                <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">{{ $subject->name ?? '' }} — {{ $exam->name ?? '' }} ({{ $class->name ?? 'All Classes' }})</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Position</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Score</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rankings as $ranking)
                                <tr>
                                    <td style="padding: 14px 20px; font-weight: 700; font-size: 18px;">{{ $ranking['position'] }}</td>
                                    <td style="padding: 14px 20px; font-weight: 500;">{{ $ranking['student']->full_name ?? '—' }}</td>
                                    <td style="padding: 14px 20px; font-weight: 600;">{{ $ranking['average_score'] }}%</td>
                                    <td style="padding: 14px 20px;">
                                        @if($ranking['average_score'] >= 50)
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
            </div>
        </div>
    @elseif($selectedExam && $selectedClass && $selectedSubject)
        <div class="card stat-card">
            <div class="card-body" style="padding: 40px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">No score entries found for this subject, exam, and class.</p>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">Please select an exam, class, and subject above to view rankings.</p>
            </div>
        </div>
    @endif
</div>
@endsection
