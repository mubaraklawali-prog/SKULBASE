@extends('layouts.app')

@section('title', 'Dashboard - ' . ($school->name ?? 'Skulbase'))

@section('content')
@php
    $isSuperAdmin = $isSuperAdmin ?? (Auth::user()->role === 'super_admin');
    $isParent = $isParent ?? (Auth::user()->role === 'parent');
@endphp

@if($isSuperAdmin)
    {{-- ═══════════ SUPER ADMIN DASHBOARD ═══════════ --}}
    <x-dashboard.welcome-banner :superAdmin="true" />

    {{-- KPI Cards Row 1 --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Total Schools"
                :value="number_format($platformStats['total_schools'])"
                color="purple"
                description="All registered schools"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Active Schools"
                :value="number_format($platformStats['active_schools'])"
                color="green"
                description="Currently active"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Pending Approvals"
                :value="number_format($platformStats['pending_schools'])"
                color="warning"
                description="Awaiting review"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Total Users"
                :value="number_format($platformStats['total_users'])"
                color="info"
                description="All platform users"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
    </div>

    {{-- KPI Cards Row 2 --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Total Students"
                :value="number_format($platformStats['total_students'])"
                color="blue"
                description="Across all schools"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Total Teachers"
                :value="number_format($platformStats['total_teachers'])"
                color="green"
                description="Across all schools"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="7" x2="16" y2="7"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Total Revenue"
                value="₦{{ number_format($platformStats['total_revenue']) }}"
                color="secondary"
                description="All fee payments"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
    </div>

    <div class="ds-dashboard-grid ds-dashboard-grid--sidebar mb-4">
        {{-- Recent Schools --}}
        <x-dashboard.widget-card title="Recent Schools" :href="route('schools.index')" hrefLabel="View All">
            @if($recentSchools->isEmpty())
                <x-dashboard.empty-state message="No schools registered yet" icon="empty" size="sm" />
            @else
                @foreach($recentSchools as $s)
                    <div class="ds-school-item">
                        <div class="ds-school-avatar">{{ substr($s->name, 0, 2) }}</div>
                        <div class="ds-school-info">
                            <p class="ds-school-name">{{ $s->name }}</p>
                            <p class="ds-school-meta">{{ $s->email ?? 'No email' }} &middot; {{ $s->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="sb-badge {{ $s->is_active ? 'sb-badge-success' : 'sb-badge-neutral' }}">
                            {{ $s->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                @endforeach
            @endif
        </x-dashboard.widget-card>

        {{-- Quick Actions --}}
        <x-dashboard.widget-card title="Quick Actions">
            <div class="ds-quick-actions">
                <a href="{{ route('schools.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Manage Schools</span>
                </a>
                <a href="{{ route('pending-schools.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Review Pending</span>
                </a>
                <a href="{{ route('plans.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Manage Plans</span>
                </a>
                <a href="{{ route('subscriptions.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--info-light); color: var(--info);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 4H3a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Subscriptions</span>
                </a>
            </div>
        </x-dashboard.widget-card>
    </div>

    {{-- Activity --}}
    <div class="ds-dashboard-grid ds-dashboard-grid--full mb-4">
        <x-dashboard.widget-card title="Recent Platform Activity">
            @if($recentActivity->isEmpty())
                <x-dashboard.empty-state message="No recent activity" icon="calendar" size="sm" />
            @else
                <x-dashboard.activity-timeline :items="$recentActivity" />
            @endif
        </x-dashboard.widget-card>
    </div>

    {{-- Super Admin Charts Row 1 --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Student Growth</h3>
                        <p class="ds-chart-card-subtitle">Platform-wide student registrations (last 12 months)</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="saChartStudentGrowth"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Student Gender</h3>
                        <p class="ds-chart-card-subtitle">Distribution across platform</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="saChartGenderDistribution"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Super Admin Charts Row 2 --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-6 col-lg-6">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Revenue Trend</h3>
                        <p class="ds-chart-card-subtitle">Monthly fee collections (last 12 months)</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="saChartRevenueTrend"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">School Registrations</h3>
                        <p class="ds-chart-card-subtitle">Monthly (last 12 months)</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="saChartSchoolGrowth"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Teacher Growth</h3>
                        <p class="ds-chart-card-subtitle">Monthly (last 12 months)</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="saChartTeacherGrowth"></canvas>
                </div>
            </div>
        </div>
    </div>

@elseif($isParent)
    {{-- ═══════════ PARENT DASHBOARD ═══════════ --}}
    <x-dashboard.welcome-banner :school="$school" :subscription="$subscription" :schoolSetting="$schoolSetting" />

    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="My Children"
                :value="$parentStats['total_children']"
                color="purple"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Today's Attendance"
                value="{{ $parentStats['today_attendance_rate'] }}%"
                color="green"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Fees Paid"
                value="₦{{ number_format($parentStats['total_paid']) }}"
                color="green"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Outstanding Fees"
                value="₦{{ number_format($parentStats['outstanding']) }}"
                color="red"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <x-dashboard.widget-card title="My Children">
                @forelse($children as $child)
                    <div class="ds-child-card mb-3">
                        <div class="ds-child-header">
                            <div class="ds-child-avatar">
                                {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="ds-child-name">{{ $child->full_name }}</h5>
                                <p class="ds-child-detail">{{ $child->admission_number }} &middot; {{ $child->schoolClass->name ?? '' }}</p>
                            </div>
                        </div>

                        <div class="ds-child-stats">
                            <div class="ds-child-stat">
                                <div class="ds-child-stat-value">{{ $child->present_days ?? 0 }}/{{ $child->total_attendance_days ?? 0 }}</div>
                                <div class="ds-child-stat-label">Attendance</div>
                            </div>
                            <div class="ds-child-stat">
                                @php
                                    $childAttendRate = ($child->total_attendance_days ?? 0) > 0
                                        ? round(($child->present_days ?? 0) / $child->total_attendance_days * 100)
                                        : 0;
                                @endphp
                                <div class="ds-child-stat-value" style="color: {{ $childAttendRate >= 75 ? 'var(--success)' : ($childAttendRate >= 50 ? 'var(--warning)' : 'var(--danger)') }};">{{ $childAttendRate }}%</div>
                                <div class="ds-child-stat-label">Rate</div>
                            </div>
                        </div>

                        <div class="ds-child-links">
                            <a href="{{ route('parent.attendance.index', ['student_id' => $child->id]) }}" class="ds-child-link">
                                <span>Attendance</span>
                                <span class="ds-child-link-arrow">&rarr;</span>
                            </a>
                            <a href="{{ route('parent.results.index', ['student_id' => $child->id]) }}" class="ds-child-link">
                                <span>Results</span>
                                <span class="ds-child-link-arrow">&rarr;</span>
                            </a>
                            <a href="{{ route('parent.fees.index', ['student_id' => $child->id]) }}" class="ds-child-link">
                                <span>Fees</span>
                                <span class="ds-child-link-arrow">&rarr;</span>
                            </a>
                            <a href="{{ route('parent.assignments.index', ['student_id' => $child->id]) }}" class="ds-child-link">
                                <span>Assignments</span>
                                <span class="ds-child-link-arrow">&rarr;</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <x-dashboard.empty-state message="No children linked to your account" icon="users" size="sm" />
                @endforelse
            </x-dashboard.widget-card>
        </div>

        <div class="col-lg-5">
            <x-dashboard.widget-card title="Recent Activity" class="mb-4">
                @if($recentActivity->isEmpty())
                    <x-dashboard.empty-state message="No recent activity" size="sm" />
                @else
                    <x-dashboard.activity-timeline :items="$recentActivity" />
                @endif
            </x-dashboard.widget-card>

            @if($upcomingEvents->isNotEmpty())
                <x-dashboard.widget-card title="Upcoming Events">
                    @foreach($upcomingEvents as $event)
                        <div class="ds-event-item">
                            <div class="ds-event-date-box">
                                <span class="ds-event-day">{{ $event->event_date->format('d') }}</span>
                                <span class="ds-event-month">{{ $event->event_date->format('M') }}</span>
                            </div>
                            <div class="ds-event-info">
                                <p class="ds-event-title">{{ $event->title }}</p>
                                @if($event->description)
                                    <p class="ds-event-desc">{{ Str::limit($event->description, 60) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </x-dashboard.widget-card>
            @endif
        </div>
    </div>

@else
    {{-- ═══════════ SCHOOL ADMIN DASHBOARD ═══════════ --}}
    <x-dashboard.welcome-banner :school="$school" :subscription="$subscription" :schoolSetting="$schoolSetting" />

    {{-- KPI Cards Row 1 --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Total Students"
                :value="number_format($stats['total_students'])"
                color="purple"
                description="{{ $stats['active_students'] }} active"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Total Teachers"
                :value="number_format($stats['total_teachers'])"
                color="green"
                description="{{ $stats['active_teachers'] }} active"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="7" x2="16" y2="7"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Today's Attendance"
                value="{{ $stats['today_attendance_rate'] }}%"
                color="info"
                description="Present today"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Pending Admissions"
                :value="number_format($schoolAdminStats['pending_admissions'])"
                color="warning"
                description="Awaiting review"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
    </div>

    {{-- KPI Cards Row 2 --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Fees Collected"
                value="₦{{ number_format($stats['total_collected']) }}"
                color="green"
                description="{{ number_format($stats['total_fee_payments']) }} payments"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Outstanding Fees"
                value="₦{{ number_format($stats['total_outstanding']) }}"
                color="red"
                description="Pending collection"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Total Parents"
                :value="number_format($schoolAdminStats['total_parents'])"
                color="blue"
                description="Registered parents"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <x-dashboard.stat-card
                title="Total Classes"
                :value="number_format($stats['total_classes'])"
                color="indigo"
                description="{{ $stats['total_subjects'] }} subjects"
            >
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </x-slot:icon>
            </x-dashboard.stat-card>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Student Enrollment Trend</h3>
                        <p class="ds-chart-card-subtitle">Monthly student registrations (last 6 months)</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="chartStudentGrowth"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Students by Class</h3>
                        <p class="ds-chart-card-subtitle">Active students per class</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="chartStudentsByClass"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Fee Collection Trend</h3>
                        <p class="ds-chart-card-subtitle">Monthly fee collections (last 6 months)</p>
                    </div>
                    <div class="ds-chart-card-actions">
                        <a href="{{ route('fees.dashboard') }}" class="ds-widget-card-link">View Report &rarr;</a>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="chartFeeCollection"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            {{-- Students by Gender --}}
            <div class="ds-chart-card mb-4">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Students by Gender</h3>
                        <p class="ds-chart-card-subtitle">Gender distribution</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 220px;">
                    <canvas id="chartGenderDistribution"></canvas>
                </div>
            </div>
            {{-- Academic Overview --}}
            <div class="ds-widget-card">
                <div class="ds-widget-card-header">
                    <h3 class="ds-widget-card-title">Academic Overview</h3>
                </div>
                <div class="ds-widget-card-body">
                    @if($academicSummary)
                        <div class="ds-academic-overview">
                            <div class="ds-academic-item">
                                <span class="ds-academic-label">Latest Exam</span>
                                <span class="ds-academic-value">{{ $academicSummary['exam_name'] }}</span>
                            </div>
                            <div class="ds-academic-item">
                                <span class="ds-academic-label">Average Score</span>
                                <span class="ds-academic-value ds-academic-value--highlight">{{ $academicSummary['avg_score'] }}%</span>
                            </div>
                            <div class="ds-academic-item">
                                <span class="ds-academic-label">Results Entered</span>
                                <span class="ds-academic-value">{{ number_format($academicSummary['total_results']) }}</span>
                            </div>
                            <div class="ds-academic-item">
                                <span class="ds-academic-label">Total Subjects</span>
                                <span class="ds-academic-value">{{ number_format($stats['total_subjects']) }}</span>
                            </div>
                        </div>
                    @else
                        <x-dashboard.empty-state message="No exam data available yet" icon="clipboard" size="sm" />
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Analytics Row --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-6 col-lg-6">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Attendance Trend</h3>
                        <p class="ds-chart-card-subtitle">Monthly attendance rate (last 6 months)</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 260px;">
                    <canvas id="chartAttendanceTrend"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Admissions Trend</h3>
                        <p class="ds-chart-card-subtitle">Monthly admissions (last 6 months)</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 260px;">
                    <canvas id="chartAdmissionsTrend"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mb-4">
        <x-dashboard.widget-card title="Quick Actions">
            <div class="ds-quick-actions">
                <a href="{{ route('students.create') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Add Student</span>
                </a>
                <a href="{{ route('teachers.create') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Add Teacher</span>
                </a>
                <a href="{{ route('attendance.create') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--info-light); color: var(--info);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Attendance</span>
                </a>
                <a href="{{ route('results.scores.dashboard') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Record Score</span>
                </a>
                <a href="{{ route('announcements.create') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--danger-light); color: var(--danger);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Announce</span>
                </a>
                <a href="{{ route('classes.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Classes</span>
                </a>
                <a href="{{ route('fees.dashboard') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Fees</span>
                </a>
                <a href="{{ route('reports.dashboard') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Reports</span>
                </a>
                <a href="{{ route('admissions.index') }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Admissions</span>
                </a>
            </div>
        </x-dashboard.widget-card>
    </div>

    {{-- Content Grid: Activity + Sidebar --}}
    <div class="ds-dashboard-grid ds-dashboard-grid--sidebar mb-4">
        {{-- Recent Activity --}}
        <x-dashboard.widget-card title="Recent Activity" :href="route('students.index')" hrefLabel="View All">
            @if($recentActivity->isEmpty())
                <x-dashboard.empty-state message="No recent activity in the last 7 days" icon="calendar" size="sm" />
            @else
                <x-dashboard.activity-timeline :items="$recentActivity" />
            @endif
        </x-dashboard.widget-card>

        {{-- Sidebar Widgets --}}
        <div class="ds-dashboard-sidebar-stack">
            {{-- Recent Admissions --}}
            <x-dashboard.widget-card title="Recent Admissions">
                @if($recentAdmissions->isEmpty())
                    <x-dashboard.empty-state message="No recent admissions" icon="users" size="sm" />
                @else
                    @foreach($recentAdmissions as $admission)
                        <div class="ds-list-item">
                            <div class="ds-list-item-info">
                                <p class="ds-list-item-name">{{ $admission->full_name }}</p>
                                <p class="ds-list-item-meta">{{ $admission->application_number }} &middot; {{ $admission->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="sb-badge sb-badge-{{ $admission->status === 'approved' ? 'success' : ($admission->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($admission->status) }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </x-dashboard.widget-card>

            {{-- Quick Summary --}}
            <x-dashboard.widget-card title="Notifications">
                <div class="ds-notification-summary">
                    <a href="{{ route('admissions.index') }}" class="ds-notification-item">
                        <div class="ds-notification-icon" style="background: var(--warning-light); color: var(--warning);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                        </div>
                        <span class="ds-notification-label">Pending Admissions</span>
                        <span class="ds-notification-count">{{ $schoolAdminStats['pending_admissions'] }}</span>
                    </a>
                    <a href="{{ route('messages.inbox') }}" class="ds-notification-item">
                        <div class="ds-notification-icon" style="background: var(--info-light); color: var(--info);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <span class="ds-notification-label">Unread Messages</span>
                        <span class="ds-notification-count">{{ $schoolAdminStats['unread_messages'] }}</span>
                    </a>
                    <a href="{{ route('announcements.index') }}" class="ds-notification-item">
                        <div class="ds-notification-icon" style="background: var(--primary-light); color: var(--primary);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        </div>
                        <span class="ds-notification-label">Active Announcements</span>
                        <span class="ds-notification-count">{{ $schoolAdminStats['active_announcements'] }}</span>
                    </a>
                </div>
            </x-dashboard.widget-card>
        </div>
    </div>

    {{-- Second Content Grid: Recent Payments + Announcements --}}
    <div class="ds-dashboard-grid ds-dashboard-grid--2 mb-4">
        {{-- Recent Payments --}}
        <x-dashboard.widget-card title="Recent Payments" :href="route('fees.payments.index')" hrefLabel="View All">
            @if($recentPayments->isEmpty())
                <x-dashboard.empty-state message="No recent payments" icon="dollar" size="sm" />
            @else
                @foreach($recentPayments as $payment)
                    <div class="ds-list-item">
                        <div class="ds-list-item-info">
                            <p class="ds-list-item-name">{{ $payment->student->full_name ?? 'Unknown Student' }}</p>
                            <p class="ds-list-item-meta">{{ $payment->feeStructure->title ?? 'Fee' }} &middot; {{ $payment->payment_date->format('M d, Y') }}</p>
                        </div>
                        <span class="ds-list-item-value" style="color: var(--success);">₦{{ number_format($payment->amount_paid) }}</span>
                    </div>
                @endforeach
            @endif
        </x-dashboard.widget-card>

        {{-- Active Announcements --}}
        <x-dashboard.widget-card title="Active Announcements" :href="route('announcements.index')" hrefLabel="View All">
            @if($activeAnnouncements->isEmpty())
                <x-dashboard.empty-state message="No active announcements" icon="bell" size="sm" />
            @else
                @foreach($activeAnnouncements as $announcement)
                    <div class="ds-announcement-item">
                        <div class="ds-announcement-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        </div>
                        <div class="ds-announcement-content">
                            <p class="ds-announcement-title">{{ $announcement->title }}</p>
                            <p class="ds-announcement-meta">{{ $announcement->created_at->diffForHumans() }} &middot; {{ ucfirst($announcement->audience ?? 'everyone') }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </x-dashboard.widget-card>
    </div>

    {{-- Upcoming Events --}}
    @if($upcomingEvents->isNotEmpty())
        <div class="ds-dashboard-grid ds-dashboard-grid--full mb-4">
            <x-dashboard.widget-card title="Upcoming Events">
                @foreach($upcomingEvents as $event)
                    <div class="ds-event-item">
                        <div class="ds-event-date-box">
                            <span class="ds-event-day">{{ $event->event_date->format('d') }}</span>
                            <span class="ds-event-month">{{ $event->event_date->format('M') }}</span>
                        </div>
                        <div class="ds-event-info">
                            <p class="ds-event-title">{{ $event->title }}</p>
                            @if($event->description)
                                <p class="ds-event-desc">{{ Str::limit($event->description, 80) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </x-dashboard.widget-card>
        </div>
    @endif

    {{-- Charts Script --}}
    @push('scripts')
    <script>
        (function() {
            function init() {
                var isSuperAdmin = @json($isSuperAdmin);

                if (isSuperAdmin) {
                    var saGrowthLabels = @json($chartData['student_growth_labels'] ?? []);
                    var saGrowthData = @json($chartData['student_growth_data'] ?? []);
                    if (saGrowthLabels.length) {
                        window.SkulCharts.createAreaChart('saChartStudentGrowth', {
                            labels: saGrowthLabels,
                            datasets: [{
                                label: 'Students',
                                data: saGrowthData,
                                color: '#3B82F6',
                                backgroundColor: '#3B82F618',
                            }],
                        });
                    }

                    var genderLabels = @json($chartData['gender_labels'] ?? []);
                    var genderData = @json($chartData['gender_data'] ?? []);
                    if (genderLabels.length) {
                        window.SkulCharts.createDoughnutChart('saChartGenderDistribution', {
                            labels: genderLabels,
                            data: genderData,
                            colors: ['#3B82F6', '#EC4899'],
                        });
                    }

                    var revenueLabels = @json($chartData['revenue_labels'] ?? []);
                    var revenueData = @json($chartData['revenue_data'] ?? []);
                    if (revenueLabels.length) {
                        window.SkulCharts.createBarChart('saChartRevenueTrend', {
                            labels: revenueLabels,
                            datasets: [{
                                label: 'Revenue',
                                data: revenueData,
                                backgroundColor: '#F59E0B',
                                borderRadius: 6,
                            }],
                        });
                    }

                    var schoolGrowthLabels = @json($chartData['school_growth_labels'] ?? []);
                    var schoolGrowthData = @json($chartData['school_growth_data'] ?? []);
                    if (schoolGrowthLabels.length) {
                        window.SkulCharts.createAreaChart('saChartSchoolGrowth', {
                            labels: schoolGrowthLabels,
                            datasets: [{
                                label: 'Schools',
                                data: schoolGrowthData,
                                color: '#8B5CF6',
                                backgroundColor: '#8B5CF618',
                            }],
                        });
                    }

                    var teacherGrowthLabels = @json($chartData['teacher_growth_labels'] ?? []);
                    var teacherGrowthData = @json($chartData['teacher_growth_data'] ?? []);
                    if (teacherGrowthLabels.length) {
                        window.SkulCharts.createAreaChart('saChartTeacherGrowth', {
                            labels: teacherGrowthLabels,
                            datasets: [{
                                label: 'Teachers',
                                data: teacherGrowthData,
                                color: '#10B981',
                                backgroundColor: '#10B98118',
                            }],
                        });
                    }
                } else {
                    var growthLabels = @json($chartData['student_growth_labels'] ?? []);
                    var growthData = @json($chartData['student_growth_data'] ?? []);
                    if (growthLabels.length) {
                        window.SkulCharts.createAreaChart('chartStudentGrowth', {
                            labels: growthLabels,
                            datasets: [{
                                label: 'Students',
                                data: growthData,
                                color: '#3B82F6',
                                backgroundColor: '#3B82F618',
                            }],
                        });
                    }

                    var classLabels = @json($chartData['students_by_class_labels'] ?? []);
                    var classData = @json($chartData['students_by_class_data'] ?? []);
                    if (classLabels.length) {
                        window.SkulCharts.createDoughnutChart('chartStudentsByClass', {
                            labels: classLabels,
                            data: classData,
                        });
                    }

                    var feeLabels = @json($chartData['fee_collection_labels'] ?? []);
                    var feeData = @json($chartData['fee_collection_data'] ?? []);
                    if (feeLabels.length) {
                        window.SkulCharts.createBarChart('chartFeeCollection', {
                            labels: feeLabels,
                            datasets: [{
                                label: 'Collections',
                                data: feeData,
                                backgroundColor: '#F59E0B',
                                borderRadius: 6,
                            }],
                        });
                    }

                    var genLabels = @json($chartData['gender_labels'] ?? []);
                    var genData = @json($chartData['gender_data'] ?? []);
                    if (genLabels.length) {
                        window.SkulCharts.createDoughnutChart('chartGenderDistribution', {
                            labels: genLabels,
                            data: genData,
                            colors: ['#3B82F6', '#EC4899'],
                        });
                    }

                    var attLabels = @json($chartData['attendance_trend_labels'] ?? []);
                    var attData = @json($chartData['attendance_trend_data'] ?? []);
                    if (attLabels.length) {
                        window.SkulCharts.createAreaChart('chartAttendanceTrend', {
                            labels: attLabels,
                            datasets: [{
                                label: 'Attendance %',
                                data: attData,
                                color: '#8B5CF6',
                                backgroundColor: '#8B5CF618',
                            }],
                            options: {
                                scales: {
                                    y: {
                                        min: 0,
                                        max: 100,
                                        ticks: { callback: function(v) { return v + '%'; } },
                                    },
                                },
                            },
                        });
                    }

                    var admLabels = @json($chartData['admissions_trend_labels'] ?? []);
                    var admData = @json($chartData['admissions_trend_data'] ?? []);
                    if (admLabels.length) {
                        window.SkulCharts.createBarChart('chartAdmissionsTrend', {
                            labels: admLabels,
                            datasets: [{
                                label: 'Admissions',
                                data: admData,
                                backgroundColor: '#06B6D4',
                                borderRadius: 6,
                            }],
                        });
                    }
                }
            }
            if (!window.SkulCharts) { window.__skulChartsQueue.push(init); return; }
            init();
        })();
    </script>
    @endpush
@endif
@endsection
