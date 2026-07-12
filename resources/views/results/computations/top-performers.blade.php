@extends('layouts.app')

@section('title', 'Top Performers - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Top Performers</h2>
            <p class="text-muted mb-0">Highest scoring students</p>
        </div>
        <a href="{{ route('results.computations.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.rankings.top-performers') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Exam *</label>
                    <select name="exam_id" required style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Limit</label>
                    <select name="limit" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="10" {{ ($limit ?? 20) == 10 ? 'selected' : '' }}>Top 10</option>
                        <option value="20" {{ ($limit ?? 20) == 20 ? 'selected' : '' }}>Top 20</option>
                        <option value="50" {{ ($limit ?? 20) == 50 ? 'selected' : '' }}>Top 50</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 8px 16px; font-weight: 500; font-size: 13px;">View</button>
                </div>
            </form>
        </div>
    </div>

    @if($performers->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body">
                <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">{{ $exam->name ?? '' }} — Top {{ $limit }} Performers</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average Score</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Grade</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Position</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($performers as $index => $performer)
                                <tr>
                                    <td style="padding: 14px 20px; font-weight: 700; font-size: 16px; color: {{ $index < 3 ? '#1a1a2e' : '#6c757d' }};">{{ $index + 1 }}</td>
                                    <td style="padding: 14px 20px; font-weight: 500;">
                                        <a href="{{ route('results.computations.show', $performer) }}" style="color: #333; text-decoration: none;">{{ $performer->student->full_name ?? '—' }}</a>
                                    </td>
                                    <td style="padding: 14px 20px; color: #6c757d;">{{ $performer->schoolClass->name ?? '—' }}</td>
                                    <td style="padding: 14px 20px; font-weight: 600;">{{ number_format($performer->average_score, 1) }}%</td>
                                    <td style="padding: 14px 20px;">
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $performer->overall_grade ?? '—' }}</span>
                                    </td>
                                    <td style="padding: 14px 20px; font-weight: 600;">{{ $performer->class_position ? $this->ordinal($performer->class_position) : '—' }}</td>
                                    <td style="padding: 14px 20px; color: #6c757d;">{{ $performer->subjects_passed }}/{{ $performer->total_subjects }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($selectedExam)
        <div class="card stat-card">
            <div class="card-body" style="padding: 40px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">No report cards found for this exam.</p>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">Please select an exam above to view top performers.</p>
            </div>
        </div>
    @endif
</div>
@endsection
