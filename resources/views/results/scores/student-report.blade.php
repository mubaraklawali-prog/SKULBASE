@extends('layouts.app')

@section('title', 'Student Score Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Student Score Report</h2>
            <p class="text-muted mb-0">{{ $student->full_name ?? '' }} — {{ $student->schoolClass->name ?? '' }}</p>
        </div>
        <a href="{{ route('results.scores.history') }}" class="sb-btn sb-btn-ghost">Back to History</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-excused">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-excused">{{ $scores->count() }}</p>
                        <p class="stat-label">Total Entries</p>
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
                        <p class="stat-number sb-stat-number-present">{{ $scores->count() > 0 ? number_format($scores->avg('score'), 1) : '0' }}%</p>
                        <p class="stat-label">Average Score</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-late">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-late">{{ $scores->count() > 0 ? $scores->max('score') : '0' }}%</p>
                        <p class="stat-label">Highest Score</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-absent">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-absent">{{ $scores->count() > 0 ? $scores->min('score') : '0' }}%</p>
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
                <h5 class="fw-semibold mb-3">{{ $exam->name ?? 'Unknown Exam' }} {{ $exam->term ? '(' . $exam->term . ')' : '' }}</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Assessment</th>
                                <th>Score</th>
                                <th>Teacher</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($examScores as $entry)
                                <tr>
                                    <td class="fw-medium">{{ $entry->subject->name ?? '—' }}</td>
                                    <td class="text-muted">{{ $entry->assessmentType->name ?? '—' }} ({{ $entry->assessmentType->percentage ?? 0 }}%)</td>
                                    <td>
                                        <span class="sb-badge {{ $entry->score >= 70 ? 'sb-badge-present' : ($entry->score >= 50 ? 'sb-badge-late' : 'sb-badge-absent') }}">{{ $entry->score }}%</span>
                                    </td>
                                    <td class="text-muted">{{ $entry->teacher->full_name ?? '—' }}</td>
                                    <td class="text-muted">{{ $entry->remarks ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p>No score entries found for this student.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection