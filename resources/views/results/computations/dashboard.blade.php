@extends('layouts.app')

@section('title', 'Result Computation Dashboard - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Result Computation Dashboard</h2>
            <p class="text-muted mb-0">Compute and manage student results</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('results.computations.compute') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Compute Results</a>
            <a href="{{ route('results.rankings.top-performers') }}" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Top Performers</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0d6efd;">{{ number_format($totalReportCards) }}</p>
                        <p class="stat-label">Total Report Cards</p>
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
                        <p class="stat-number" style="color: #0f5132;">{{ number_format($publishedResults) }}</p>
                        <p class="stat-label">Published Results</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #664d03;">{{ number_format($draftResults) }}</p>
                        <p class="stat-label">Draft Results</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0d6efd;">{{ $classAverage ? number_format($classAverage, 1) . '%' : 'N/A' }}</p>
                        <p class="stat-label">Overall Average</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Top Performers</h5>
                    @if($topPerformers->isEmpty())
                        <p class="text-muted" style="margin: 0;">No results computed yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr style="background: #f8f9fa;">
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Grade</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topPerformers as $index => $performer)
                                        <tr>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $index + 1 }}</td>
                                            <td style="padding: 12px 16px; font-weight: 500;">
                                                <a href="{{ route('results.computations.show', $performer) }}" style="color: #333; text-decoration: none;">{{ $performer->student->full_name ?? '—' }}</a>
                                            </td>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $performer->schoolClass->name ?? '—' }}</td>
                                            <td style="padding: 12px 16px; font-weight: 600;">{{ number_format($performer->average_score, 1) }}%</td>
                                            <td style="padding: 12px 16px;">
                                                <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $performer->overall_grade ?? '—' }}</span>
                                            </td>
                                            <td style="padding: 12px 16px;">
                                                @if($performer->status === 'published')
                                                    <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Published</span>
                                                @elseif($performer->status === 'approved')
                                                    <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Approved</span>
                                                @else
                                                    <span style="background: #f0f2f5; color: #6c757d; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Draft</span>
                                                @endif
                                            </td>
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
                        <a href="{{ route('results.computations.compute') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                            Compute Results
                        </a>
                        <a href="{{ route('results.rankings.class') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                            Class Rankings
                        </a>
                        <a href="{{ route('results.rankings.subject') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            Subject Rankings
                        </a>
                        <a href="{{ route('results.rankings.top-performers') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            Top Performers
                        </a>
                        <a href="{{ route('results.analytics') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                            Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($recentComputations->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body">
                <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Recent Computations</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Exam</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Grade</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Position</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentComputations as $record)
                                <tr>
                                    <td style="padding: 12px 16px; font-weight: 500;">
                                        <a href="{{ route('results.computations.show', $record) }}" style="color: #333; text-decoration: none;">{{ $record->student->full_name ?? '—' }}</a>
                                    </td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $record->exam->name ?? '—' }}</td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $record->schoolClass->name ?? '—' }}</td>
                                    <td style="padding: 12px 16px; font-weight: 600;">{{ number_format($record->average_score, 1) }}%</td>
                                    <td style="padding: 12px 16px;">
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $record->overall_grade ?? '—' }}</span>
                                    </td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $record->class_position ? $this->ordinal($record->class_position) : '—' }}</td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $record->updated_at->format('M d, Y') }}</td>
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
