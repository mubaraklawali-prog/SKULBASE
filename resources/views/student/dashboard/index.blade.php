@extends('layouts.app')

@section('title', 'Student Dashboard - Skulbase')

@section('content')
@php
    $school = Auth::user()->school;
    $schoolSetting = $school?->setting;
@endphp

<x-dashboard.welcome-banner :school="$school" :schoolSetting="$schoolSetting" />

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-dashboard.stat-card
            title="Attendance Rate"
            value="{{ $rate }}%"
            color="green"
            description="{{ $presentDays }}/{{ $totalDays }} days present"
        >
            <x-slot:icon>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </x-slot:icon>
        </x-dashboard.stat-card>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-dashboard.stat-card
            title="Fees Paid"
            value="₦{{ number_format($totalPaid) }}"
            color="purple"
            description="Total payments made"
        >
            <x-slot:icon>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </x-slot:icon>
        </x-dashboard.stat-card>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-dashboard.stat-card
            title="Outstanding Fees"
            value="₦{{ number_format($outstanding) }}"
            color="red"
            description="Pending payment"
        >
            <x-slot:icon>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </x-slot:icon>
        </x-dashboard.stat-card>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-dashboard.stat-card
            title="Upcoming Assignments"
            :value="$upcomingAssignments->count()"
            color="warning"
            description="Due soon"
        >
            <x-slot:icon>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            </x-slot:icon>
        </x-dashboard.stat-card>
    </div>
</div>

<div class="ds-dashboard-grid ds-dashboard-grid--sidebar mb-4">
    {{-- Report Card + Recent Scores --}}
    <div>
        @if($latestReportCard)
            <x-dashboard.widget-card title="Latest Report Card" class="mb-4">
                <div class="ds-report-highlight">
                    <div class="ds-report-highlight-info">
                        <h5 class="ds-report-highlight-title">{{ $latestReportCard->exam->name ?? 'Report Card' }}</h5>
                        <p class="ds-report-highlight-detail">Average: {{ $latestReportCard->average_score ?? '—' }}% &middot; Grade: {{ $latestReportCard->overall_grade ?? '—' }}</p>
                    </div>
                    <a href="{{ route('student.results.report-card', $latestReportCard->id) }}" class="sb-btn sb-btn-outline-primary sb-btn-sm">View</a>
                </div>
            </x-dashboard.widget-card>
        @endif

        @if($recentResults->isNotEmpty())
            <x-dashboard.widget-card title="Recent Scores">
                @foreach($recentResults as $result)
                    <div class="ds-result-item">
                        <div class="ds-result-item-info">
                            <p class="ds-result-item-subject">{{ $result->subject->name ?? '—' }} — {{ $result->assessmentType->name ?? '—' }}</p>
                            <p class="ds-result-item-meta">{{ $result->exam->name ?? '' }}</p>
                        </div>
                        <span class="ds-result-item-score {{ $result->score >= 70 ? 'ds-result-item-score--high' : ($result->score >= 50 ? 'ds-result-item-score--mid' : 'ds-result-item-score--low') }}">
                            {{ $result->score }}%
                        </span>
                    </div>
                @endforeach
            </x-dashboard.widget-card>
        @endif
    </div>

    {{-- Sidebar --}}
    <div>
        @if($upcomingAssignments->isNotEmpty())
            <x-dashboard.widget-card title="Upcoming Assignments" class="mb-4">
                @foreach($upcomingAssignments as $assignment)
                    <div class="ds-list-item">
                        <div class="ds-list-item-info">
                            <p class="ds-list-item-name">{{ $assignment->title }}</p>
                            <p class="ds-list-item-meta">{{ $assignment->subject->name ?? '' }} &middot; Due {{ $assignment->due_date->format('M d') }}</p>
                        </div>
                        <span class="sb-badge {{ $assignment->due_date->isPast() ? 'sb-badge-danger' : 'sb-badge-warning' }}">
                            {{ $assignment->due_date->isPast() ? 'Overdue' : 'Pending' }}
                        </span>
                    </div>
                @endforeach
            </x-dashboard.widget-card>
        @endif

        <x-dashboard.widget-card title="Quick Links">
            <div class="ds-quick-actions">
                <a href="{{ route('student.attendance.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Attendance</span>
                </a>
                <a href="{{ route('student.results.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Results</span>
                </a>
                <a href="{{ route('student.assignments.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--info-light); color: var(--info);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Assignments</span>
                </a>
                <a href="{{ route('student.fees.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Fees</span>
                </a>
                <a href="{{ route('student.timetable.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--danger-light); color: var(--danger);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                    </div>
                    <span class="ds-quick-action-label">Timetable</span>
                </a>
            </div>
        </x-dashboard.widget-card>
    </div>
</div>
@endsection
