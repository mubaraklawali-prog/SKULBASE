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
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0d6efd;">{{ $totalExams }}</p>
                        <p class="stat-label">Total Exams</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #664d03;">{{ $totalAssessmentTypes }}</p>
                        <p class="stat-label">Assessment Types</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0f5132;">{{ $totalGradingRules }}</p>
                        <p class="stat-label">Grading Rules</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 22px; color: #0f5132;">{{ $activeExams }}</p>
                    <p class="stat-label">Active Exams</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 22px; color: #0f5132;">{{ $activeAssessmentTypes }}</p>
                    <p class="stat-label">Active Assessment Types</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 22px; color: {{ $totalPercentage == 100 ? '#0f5132' : '#842029' }};">{{ number_format($totalPercentage, 1) }}%</p>
                    <p class="stat-label">Total Assessment Weight</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Recent Exams</h5>
                    @if($recentExams->isEmpty())
                        <p class="text-muted" style="margin: 0;">No exams created yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr style="background: #f8f9fa;">
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Name</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Term</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Session</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Dates</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentExams as $exam)
                                        <tr>
                                            <td style="padding: 12px 16px; font-weight: 500;">{{ $exam->name }}</td>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $exam->term ?? '—' }}</td>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $exam->session ?? '—' }}</td>
                                            <td style="padding: 12px 16px; color: #6c757d; font-size: 13px;">
                                                {{ $exam->start_date ? $exam->start_date->format('M d') : '—' }}
                                                {{ $exam->end_date ? ' - ' . $exam->end_date->format('M d, Y') : '' }}
                                            </td>
                                            <td style="padding: 12px 16px;">
                                                @if($exam->status)
                                                    <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                                                @else
                                                    <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
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
