@extends('layouts.app')

@section('title', 'Student Score Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Student Score Report</h2>
            <p class="text-muted mb-0">{{ $student->full_name ?? '' }} — {{ $student->schoolClass->name ?? '' }}</p>
        </div>
        <a href="{{ route('results.scores.history') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to History</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0d6efd;">{{ $scores->count() }}</p>
                        <p class="stat-label">Total Entries</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0f5132;">{{ $scores->count() > 0 ? number_format($scores->avg('score'), 1) : '0' }}%</p>
                        <p class="stat-label">Average Score</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #664d03;">{{ $scores->count() > 0 ? $scores->max('score') : '0' }}%</p>
                        <p class="stat-label">Highest Score</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f8d7da; color: #842029;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #842029;">{{ $scores->count() > 0 ? $scores->min('score') : '0' }}%</p>
                        <p class="stat-label">Lowest Score</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @forelse($groupedScores as $examId => $examScores)
        @php $exam = $examScores->first()->exam; @endphp
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">{{ $exam->name ?? 'Unknown Exam' }} {{ $exam->term ? '(' . $exam->term . ')' : '' }}</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Subject</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Assessment</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Score</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Teacher</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($examScores as $entry)
                                <tr>
                                    <td style="padding: 12px 16px; font-weight: 500;">{{ $entry->subject->name ?? '—' }}</td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $entry->assessmentType->name ?? '—' }} ({{ $entry->assessmentType->percentage ?? 0 }}%)</td>
                                    <td style="padding: 12px 16px;">
                                        <span style="background: {{ $entry->score >= 70 ? '#d1e7dd' : ($entry->score >= 50 ? '#fff3cd' : '#f8d7da') }}; color: {{ $entry->score >= 70 ? '#0f5132' : ($entry->score >= 50 ? '#664d03' : '#842029') }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $entry->score }}%</span>
                                    </td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $entry->teacher->full_name ?? '—' }}</td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $entry->remarks ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="card stat-card">
            <div class="card-body" style="padding: 40px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">No score entries found for this student.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
