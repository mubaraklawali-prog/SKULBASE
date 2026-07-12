@extends('layouts.app')

@section('title', 'Score Entry Dashboard - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Score Entry Dashboard</h2>
            <p class="text-muted mb-0">Overview of student score entries</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('results.scores.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Enter Scores</a>
            <a href="{{ route('results.scores.history') }}" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">View History</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0d6efd;">{{ number_format($totalEntries) }}</p>
                        <p class="stat-label">Total Score Entries</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0f5132;">{{ $examsWithScores }}</p>
                        <p class="stat-label">Exams with Scores</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #664d03;">{{ $subjectsWithScores }}</p>
                        <p class="stat-label">Subjects with Scores</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f8d7da; color: #842029;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #842029;">{{ $pendingEntries }}</p>
                        <p class="stat-label">Pending Entries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Recent Score Entries</h5>
                    @if($recentEntries->isEmpty())
                        <p class="text-muted" style="margin: 0;">No score entries yet. <a href="{{ route('results.scores.create') }}" style="color: #4f9cf7; text-decoration: none;">Enter scores now</a></p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr style="background: #f8f9fa;">
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Subject</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Exam</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Assessment</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEntries as $entry)
                                        <tr>
                                            <td style="padding: 12px 16px; font-weight: 500;">{{ $entry->student->full_name ?? '—' }}</td>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $entry->schoolClass->name ?? '—' }}</td>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $entry->subject->name ?? '—' }}</td>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $entry->exam->name ?? '—' }}</td>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $entry->assessmentType->name ?? '—' }}</td>
                                            <td style="padding: 12px 16px; font-weight: 600;">{{ $entry->score }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Quick Actions</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('results.scores.create') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Enter New Scores
                        </a>
                        <a href="{{ route('results.scores.edit') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                            Edit Existing Scores
                        </a>
                        <a href="{{ route('results.scores.history') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Score History
                        </a>
                        <a href="{{ route('results.reports.subject') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            Subject Report
                        </a>
                        <a href="{{ route('results.reports.class') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                            Class Report
                        </a>
                        <a href="{{ route('results.reports.exam') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Exam Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($topScorers->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body">
                <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Top Performers</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average Score</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topScorers as $index => $scorer)
                                <tr>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $index + 1 }}</td>
                                    <td style="padding: 12px 16px; font-weight: 500;">{{ $scorer->student->full_name ?? '—' }}</td>
                                    <td style="padding: 12px 16px;">
                                        <span style="background: {{ $scorer->avg_score >= 70 ? '#d1e7dd' : ($scorer->avg_score >= 50 ? '#fff3cd' : '#f8d7da') }}; color: {{ $scorer->avg_score >= 70 ? '#0f5132' : ($scorer->avg_score >= 50 ? '#664d03' : '#842029') }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ number_format($scorer->avg_score, 1) }}%</span>
                                    </td>
                                    <td style="padding: 12px 16px; text-align: right;">
                                        <a href="{{ route('results.scores.student-report', $scorer->student) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">View Report</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
