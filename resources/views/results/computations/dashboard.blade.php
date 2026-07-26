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
            <a href="{{ route('results.computations.compute') }}" class="sb-btn sb-btn-primary">Compute Results</a>
            <a href="{{ route('results.rankings.top-performers') }}" class="sb-btn sb-btn-dark">Top Performers</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-excused">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-excused">{{ number_format($totalReportCards) }}</p>
                        <p class="stat-label">Total Report Cards</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-present">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-present">{{ number_format($publishedResults) }}</p>
                        <p class="stat-label">Published Results</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-late">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-late">{{ number_format($draftResults) }}</p>
                        <p class="stat-label">Draft Results</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-excused">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-excused">{{ $classAverage ? number_format($classAverage, 1) . '%' : 'N/A' }}</p>
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
                    <h5 class="fw-semibold mb-3">Top Performers</h5>
                    @if($topPerformers->isEmpty())
                        <p class="text-muted mb-0">No results computed yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 sb-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Class</th>
                                        <th>Average</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topPerformers as $index => $performer)
                                        <tr>
                                            <td class="text-muted">{{ $index + 1 }}</td>
                                            <td class="fw-medium">
                                                <a href="{{ route('results.computations.show', $performer) }}" class="text-decoration-none">{{ $performer->student->full_name ?? '—' }}</a>
                                            </td>
                                            <td class="text-muted">{{ $performer->schoolClass->name ?? '—' }}</td>
                                            <td class="fw-semibold">{{ number_format($performer->average_score, 1) }}%</td>
                                            <td>
                                                <span class="sb-badge sb-badge-excused">{{ $performer->overall_grade ?? '—' }}</span>
                                            </td>
                                            <td>
                                                @if($performer->status === 'published')
                                                    <span class="sb-badge sb-badge-present">Published</span>
                                                @elseif($performer->status === 'approved')
                                                    <span class="sb-badge sb-badge-late">Approved</span>
                                                @else
                                                    <span class="sb-badge sb-badge-secondary">Draft</span>
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
                    <h5 class="fw-semibold mb-3">Quick Actions</h5>
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
                <h5 class="fw-semibold mb-3">Recent Computations</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Exam</th>
                                <th>Class</th>
                                <th>Average</th>
                                <th>Grade</th>
                                <th>Position</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentComputations as $record)
                                <tr>
                                    <td class="fw-medium">
                                        <a href="{{ route('results.computations.show', $record) }}" class="text-decoration-none">{{ $record->student->full_name ?? '—' }}</a>
                                    </td>
                                    <td class="text-muted">{{ $record->exam->name ?? '—' }}</td>
                                    <td class="text-muted">{{ $record->schoolClass->name ?? '—' }}</td>
                                    <td class="fw-semibold">{{ number_format($record->average_score, 1) }}%</td>
                                    <td>
                                        <span class="sb-badge sb-badge-excused">{{ $record->overall_grade ?? '—' }}</span>
                                    </td>
                                    <td class="text-muted">
                                        @if($record->class_position)
                                            @php
                                                $pos = $record->class_position;
                                                $suffix = match(true) { $pos % 100 >= 11 && $pos % 100 <= 13 => 'th', $pos % 10 === 1 => 'st', $pos % 10 === 2 => 'nd', $pos % 10 === 3 => 'rd', default => 'th' };
                                            @endphp
                                            {{ $pos . $suffix }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $record->updated_at->format('M d, Y') }}</td>
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