@extends('layouts.app')

@section('title', 'Results Dashboard - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Results Dashboard</h2>
            <p class="text-muted mb-0">Manage exams, assessments, and grading</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-excused">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-excused">{{ $totalExams }}</p>
                        <p class="stat-label">Total Exams</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-late">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-late">{{ $totalAssessmentTypes }}</p>
                        <p class="stat-label">Assessment Types</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-present">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-present">{{ $totalGradingRules }}</p>
                        <p class="stat-label">Grading Rules</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body text-center p-4">
                    <p class="stat-number sb-stat-number-present">{{ $activeExams }}</p>
                    <p class="stat-label">Active Exams</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body text-center p-4">
                    <p class="stat-number sb-stat-number-present">{{ $activeAssessmentTypes }}</p>
                    <p class="stat-label">Active Assessment Types</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body text-center p-4">
                    <p class="stat-number {{ $totalPercentage == 100 ? 'sb-stat-number-present' : 'sb-stat-number-absent' }}">{{ number_format($totalPercentage, 1) }}%</p>
                    <p class="stat-label">Total Assessment Weight</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Recent Exams</h5>
                    @if($recentExams->isEmpty())
                        <p class="text-muted mb-0">No exams created yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 sb-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Term</th>
                                        <th>Session</th>
                                        <th>Dates</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentExams as $exam)
                                        <tr>
                                            <td class="fw-medium">{{ $exam->name }}</td>
                                            <td class="text-muted">{{ $exam->term ?? '—' }}</td>
                                            <td class="text-muted">{{ $exam->session ?? '—' }}</td>
                                            <td class="text-muted small">
                                                {{ $exam->start_date ? $exam->start_date->format('M d') : '—' }}
                                                {{ $exam->end_date ? ' - ' . $exam->end_date->format('M d, Y') : '' }}
                                            </td>
                                            <td>
                                                @if($exam->status)
                                                    <span class="sb-badge sb-badge-present">Active</span>
                                                @else
                                                    <span class="sb-badge sb-badge-absent">Inactive</span>
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
                        <a href="{{ route('results.assessment-types.index') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            Assessment Types
                        </a>
                        <a href="{{ route('results.exams.index') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Exams
                        </a>
                        <a href="{{ route('results.grading-systems.index') }}" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Grading System
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection