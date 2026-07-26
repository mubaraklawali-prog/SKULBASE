@extends('layouts.app')

@section('title', 'Teacher Dashboard - Skulbase')

@section('content')
@php
    $school = Auth::user()->school;
    $schoolSetting = $school?->setting;
@endphp

<x-dashboard.welcome-banner :school="$school" :schoolSetting="$schoolSetting" />

{{-- Pending Assignments Alert --}}
@if($pendingAssignments > 0)
    <div class="sb-alert sb-alert-warning mb-4">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span>You have {{ $pendingAssignments }} overdue assignment{{ $pendingAssignments > 1 ? 's' : '' }} that need attention.</span>
    </div>
@endif

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-dashboard.stat-card
            title="My Classes"
            :value="$teacherStats['total_classes']"
            color="purple"
            description="Assigned classes"
        >
            <x-slot:icon>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
            </x-slot:icon>
        </x-dashboard.stat-card>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-dashboard.stat-card
            title="My Students"
            :value="$teacherStats['total_students']"
            color="green"
            description="Across all classes"
        >
            <x-slot:icon>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </x-slot:icon>
        </x-dashboard.stat-card>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-dashboard.stat-card
            title="Today's Classes"
            :value="$teacherStats['today_classes']"
            color="info"
            description="Scheduled today"
        >
            <x-slot:icon>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </x-slot:icon>
        </x-dashboard.stat-card>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-dashboard.stat-card
            title="Attendance This Week"
            :value="$recentAttendanceCount"
            color="warning"
            description="Records marked"
        >
            <x-slot:icon>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </x-slot:icon>
        </x-dashboard.stat-card>
    </div>
</div>

{{-- Today's Timetable --}}
@if($todayTimetable->isNotEmpty())
    <div class="mb-4">
        <x-dashboard.widget-card title="Today's Schedule" subtitle="{{ Carbon::now()->format('l, F j, Y') }}">
            <div class="ds-timetable-list">
                @foreach($todayTimetable as $slot)
                    <div class="ds-timetable-slot">
                        <div class="ds-timetable-time">
                            <span class="ds-timetable-period-name">{{ $slot->period->name ?? '' }}</span>
                            <span class="ds-timetable-period-time">{{ $slot->period->start_time ?? '' }} - {{ $slot->period->end_time ?? '' }}</span>
                        </div>
                        <div class="ds-timetable-divider"></div>
                        <div class="ds-timetable-details">
                            <span class="ds-timetable-subject">{{ $slot->subject->name ?? 'N/A' }}</span>
                            <span class="ds-timetable-class">{{ $slot->schoolClass->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-dashboard.widget-card>
    </div>
@endif

{{-- Quick Actions --}}
<div class="mb-4">
    <x-dashboard.widget-card title="Quick Actions">
        <div class="ds-quick-actions">
            @if($teacher->can_mark_attendance)
                <a href="{{ route('teacher.attendance.create') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Take Attendance</span>
                </a>
            @endif
            <a href="{{ route('teacher.scores.create') }}" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </div>
                <span class="ds-quick-action-label">Enter Scores</span>
            </a>
            <a href="{{ route('assignments.create') }}" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: var(--info-light); color: var(--info);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </div>
                <span class="ds-quick-action-label">New Assignment</span>
            </a>
            <a href="{{ route('teacher.timetable.index') }}" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                </div>
                <span class="ds-quick-action-label">View Timetable</span>
            </a>
            <a href="{{ route('messages.inbox') }}" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: var(--danger-light); color: var(--danger);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <span class="ds-quick-action-label">Messages</span>
            </a>
            @if($teacher->can_mark_attendance)
            <a href="{{ route('teacher.attendance.index') }}" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: #EEF2FF; color: #6366F1;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <span class="ds-quick-action-label">Attendance History</span>
            </a>
            @endif
        </div>
    </x-dashboard.widget-card>
</div>

{{-- Content Grid --}}
<div class="ds-dashboard-grid ds-dashboard-grid--sidebar mb-4">
    {{-- My Classes --}}
    <x-dashboard.widget-card title="My Classes">
        @forelse($classes as $class)
            <div class="ds-list-item">
                <div class="ds-list-item-info">
                    <p class="ds-list-item-name">{{ $class->name }}@if($class->section) — {{ $class->section }}@endif</p>
                    <p class="ds-list-item-meta">{{ $class->students_count }} students</p>
                </div>
                <div class="ds-list-item-value">
                    <span class="sb-badge sb-badge-primary">{{ $class->students_count }}</span>
                </div>
            </div>
        @empty
            <x-dashboard.empty-state message="No classes assigned yet" icon="empty" size="sm" />
        @endforelse
    </x-dashboard.widget-card>

    {{-- Sidebar: My Subjects + Announcements --}}
    <div class="ds-dashboard-sidebar-stack">
        <x-dashboard.widget-card title="My Subjects">
            @forelse($subjects as $subject)
                <div class="ds-list-item">
                    <div class="ds-list-item-info">
                        <p class="ds-list-item-name">{{ $subject->name }}</p>
                    </div>
                    <span class="sb-badge sb-badge-secondary">Active</span>
                </div>
            @empty
                <x-dashboard.empty-state message="No subjects assigned yet" icon="empty" size="sm" />
            @endforelse
        </x-dashboard.widget-card>

        @if($activeAnnouncements->isNotEmpty())
            <x-dashboard.widget-card title="Announcements">
                @foreach($activeAnnouncements as $announcement)
                    <div class="ds-announcement-item">
                        <div class="ds-announcement-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        </div>
                        <div class="ds-announcement-content">
                            <p class="ds-announcement-title">{{ $announcement->title }}</p>
                            <p class="ds-announcement-meta">{{ $announcement->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </x-dashboard.widget-card>
        @endif
    </div>
</div>

{{-- Bottom Grid --}}
<div class="ds-dashboard-grid ds-dashboard-grid--2 mb-4">
    {{-- Upcoming Assignments --}}
    <x-dashboard.widget-card title="Upcoming Assignments" :href="route('assignments.index')" hrefLabel="View All">
        @forelse($upcomingAssignments as $assignment)
            <div class="ds-list-item">
                <div class="ds-list-item-info">
                    <p class="ds-list-item-name">{{ $assignment->title }}</p>
                    <p class="ds-list-item-meta">{{ $assignment->schoolClass->name ?? '' }} &middot; {{ $assignment->subject->name ?? '' }}</p>
                </div>
                <span class="sb-badge sb-badge-warning">Due {{ $assignment->due_date->format('M d') }}</span>
            </div>
        @empty
            <x-dashboard.empty-state message="No upcoming assignments" size="sm" />
        @endforelse
    </x-dashboard.widget-card>

    {{-- Recent Scores --}}
    <x-dashboard.widget-card title="Recent Score Entries" :href="route('teacher.scores.history')" hrefLabel="View All">
        @forelse($recentResults as $result)
            <div class="ds-list-item">
                <div class="ds-list-item-info">
                    <p class="ds-list-item-name">{{ $result->student->full_name ?? 'N/A' }}</p>
                    <p class="ds-list-item-meta">{{ $result->subject->name ?? '' }} &middot; {{ $result->exam->name ?? '' }}</p>
                </div>
                <div class="ds-list-item-value" style="color: {{ $result->score >= 70 ? 'var(--success)' : ($result->score >= 50 ? 'var(--warning)' : 'var(--danger)') }};">
                    {{ $result->score }}%
                </div>
            </div>
        @empty
            <x-dashboard.empty-state message="No score entries yet" size="sm" />
        @endforelse
    </x-dashboard.widget-card>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.SkulCharts) return;

        const weekLabels = @json($chartData['weekly_labels']);
        const weekPresent = @json($chartData['weekly_present']);
        const weekTotal = @json($chartData['weekly_total']);
        if (weekLabels.length) {
            window.SkulCharts.createBarChart('chartWeeklyAttendance', {
                labels: weekLabels,
                datasets: [
                    { label: 'Total', data: weekTotal, backgroundColor: '#E2E8F0', borderRadius: 6 },
                    { label: 'Present', data: weekPresent, backgroundColor: '#10B981', borderRadius: 6 },
                ],
            });
        }
    });
</script>
@endpush
@endsection
