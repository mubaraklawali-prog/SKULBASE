@extends('layouts.app')

@section('title', 'Parent Dashboard - Skulbase')

@section('content')
@php
    $schoolSetting = $school?->setting;
    $subscription = $school?->activeSubscription;
@endphp

<x-dashboard.welcome-banner :school="$school" :subscription="$subscription" :schoolSetting="$schoolSetting" />

@if($children->isEmpty())
    <div class="ds-dashboard-grid ds-dashboard-grid--full mb-4">
        <x-dashboard.widget-card title="My Children">
            <x-dashboard.empty-state message="No children linked to your account. Please contact your school administrator." icon="users" />
        </x-dashboard.widget-card>
    </div>
@else

    {{-- KPI Cards Row 1 --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="ds-stat-card">
                <div class="ds-stat-card-header">
                    <span class="ds-stat-card-title">My Children</span>
                    <div class="ds-stat-card-icon" style="background: rgba(91,33,255,0.08); color: #5B21FF;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>
                <div class="ds-stat-card-value">{{ $parentStats['total_children'] }}</div>
                <div class="ds-stat-card-footer">
                    <span class="ds-stat-desc">Enrolled students</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="ds-stat-card">
                <div class="ds-stat-card-header">
                    <span class="ds-stat-card-title">Attendance Rate</span>
                    <div class="ds-stat-card-icon" style="background: #D1FAE5; color: #10B981;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                </div>
                <div class="ds-stat-card-value" style="color: {{ $parentStats['overall_attendance_rate'] >= 75 ? 'var(--success)' : ($parentStats['overall_attendance_rate'] >= 50 ? 'var(--warning)' : 'var(--danger)') }};">{{ $parentStats['overall_attendance_rate'] }}%</div>
                <div class="ds-stat-card-footer">
                    <span class="ds-stat-desc">Last 30 days</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="ds-stat-card">
                <div class="ds-stat-card-header">
                    <span class="ds-stat-card-title">Outstanding Fees</span>
                    <div class="ds-stat-card-icon" style="background: #FEF3C7; color: #F59E0B;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                </div>
                <div class="ds-stat-card-value">{{ number_format($parentStats['outstanding_fees'], 2) }}</div>
                <div class="ds-stat-card-footer">
                    <span class="ds-stat-desc">Total balance</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="ds-stat-card">
                <div class="ds-stat-card-header">
                    <span class="ds-stat-card-title">Unread Messages</span>
                    <div class="ds-stat-card-icon" style="background: #DBEAFE; color: #3B82F6;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                </div>
                <div class="ds-stat-card-value">{{ $parentStats['unread_messages'] }}</div>
                <div class="ds-stat-card-footer">
                    <span class="ds-stat-desc">Need your attention</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Children Cards --}}
    <div class="row g-3 mb-4">
        @foreach($children as $child)
            <div class="col-md-6 col-lg-4">
                <div class="ds-child-card">
                    <div class="ds-child-header">
                        <div class="ds-child-avatar">
                            {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h5 class="ds-child-name">{{ $child->full_name }}</h5>
                            <p class="ds-child-detail">{{ $child->admission_number }}</p>
                            @if($child->schoolClass)
                                <p class="ds-child-detail">{{ $child->schoolClass->name }}{{ $child->section ? ' — ' . $child->section->name : '' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="ds-child-stats">
                        <div class="ds-child-stat">
                            <div class="ds-child-stat-value">{{ $child->present_days ?? 0 }}/{{ $child->total_attendance_days ?? 0 }}</div>
                            <div class="ds-child-stat-label">Attendance</div>
                        </div>
                        <div class="ds-child-stat">
                            @php
                                $attendRate = ($child->total_attendance_days ?? 0) > 0
                                    ? round(($child->present_days ?? 0) / $child->total_attendance_days * 100)
                                    : 0;
                            @endphp
                            <div class="ds-child-stat-value" style="color: {{ $attendRate >= 75 ? 'var(--success)' : ($attendRate >= 50 ? 'var(--warning)' : 'var(--danger)') }};">{{ $attendRate }}%</div>
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
                        <a href="{{ route('parent.timetable.index', ['student_id' => $child->id]) }}" class="ds-child-link">
                            <span>Timetable</span>
                            <span class="ds-child-link-arrow">&rarr;</span>
                        </a>
                        <a href="{{ route('parent.assignments.index', ['student_id' => $child->id]) }}" class="ds-child-link">
                            <span>Assignments</span>
                            <span class="ds-child-link-arrow">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Quick Actions --}}
    <div class="mb-4">
        <x-dashboard.widget-card title="Quick Actions">
            <div class="ds-quick-actions">
                @if($children->count() === 1)
                    @php $child = $children->first(); @endphp
                    <a href="{{ route('parent.attendance.index', ['student_id' => $child->id]) }}" class="ds-quick-action">
                        <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <span class="ds-quick-action-label">View Attendance</span>
                    </a>
                    <a href="{{ route('parent.results.index', ['student_id' => $child->id]) }}" class="ds-quick-action">
                        <div class="ds-quick-action-icon" style="background: var(--info-light); color: var(--info);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        </div>
                        <span class="ds-quick-action-label">View Results</span>
                    </a>
                    <a href="{{ route('parent.fees.index', ['student_id' => $child->id]) }}" class="ds-quick-action">
                        <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        <span class="ds-quick-action-label">View Fees</span>
                    </a>
                    <a href="{{ route('parent.timetable.index', ['student_id' => $child->id]) }}" class="ds-quick-action">
                        <div class="ds-quick-action-icon" style="background: #EEF2FF; color: #6366F1;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <span class="ds-quick-action-label">Timetable</span>
                    </a>
                    <a href="{{ route('parent.assignments.index', ['student_id' => $child->id]) }}" class="ds-quick-action">
                        <div class="ds-quick-action-icon" style="background: #FCE7F3; color: #EC4899;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                        <span class="ds-quick-action-label">Assignments</span>
                    </a>
                @else
                    @foreach($children as $child)
                        <a href="{{ route('parent.results.index', ['student_id' => $child->id]) }}" class="ds-quick-action">
                            <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                                {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                            </div>
                            <span class="ds-quick-action-label">{{ $child->first_name }}'s Dashboard</span>
                        </a>
                    @endforeach
                @endif
            </div>
        </x-dashboard.widget-card>
    </div>

    {{-- Recent Results & Recent Payments --}}
    <div class="ds-dashboard-grid ds-dashboard-grid--2 mb-4">
        {{-- Recent Results --}}
        <x-dashboard.widget-card title="Recent Results">
            @forelse($recentResults as $result)
                <div class="ds-result-item">
                    <div class="ds-result-info">
                        <p class="ds-result-name">{{ $result->student->full_name ?? 'Unknown' }}</p>
                        <p class="ds-result-meta">{{ $result->subject->name ?? '' }} &middot; {{ $result->exam->name ?? '' }}</p>
                    </div>
                    <div class="ds-result-score" style="color: {{ ($result->score ?? 0) >= 50 ? 'var(--success)' : 'var(--danger)' }};">
                        {{ number_format($result->score ?? 0, 1) }}%
                    </div>
                </div>
            @empty
                <div class="ds-empty-state ds-empty-state--sm">
                    <div class="ds-empty-state-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <p class="ds-empty-state-message">No results available yet</p>
                </div>
            @endforelse
        </x-dashboard.widget-card>

        {{-- Recent Payments --}}
        <x-dashboard.widget-card title="Recent Payments">
            @forelse($recentPayments as $payment)
                <div class="ds-list-item">
                    <div class="ds-list-item-info">
                        <p class="ds-list-item-name">{{ $payment->student->full_name ?? 'Unknown' }}</p>
                        <p class="ds-list-item-meta">{{ $payment->feeStructure->title ?? 'Fee' }} &middot; {{ $payment->payment_date?->format('M d, Y') ?? '' }}</p>
                    </div>
                    <div class="ds-list-item-value" style="color: var(--success);">
                        {{ number_format($payment->amount_paid, 2) }}
                    </div>
                </div>
            @empty
                <div class="ds-empty-state ds-empty-state--sm">
                    <div class="ds-empty-state-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <p class="ds-empty-state-message">No payments recorded yet</p>
                </div>
            @endforelse
        </x-dashboard.widget-card>
    </div>

    {{-- Announcements & Events --}}
    <div class="ds-dashboard-grid ds-dashboard-grid--2 mb-4">
        {{-- Active Announcements --}}
        <x-dashboard.widget-card title="Announcements">
            @forelse($activeAnnouncements as $announcement)
                <div class="ds-list-item">
                    <div class="ds-list-item-info">
                        <p class="ds-list-item-name">{{ $announcement->title }}</p>
                        <p class="ds-list-item-meta">{{ $announcement->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="sb-badge-{{ $announcement->priority === 'high' ? 'inactive' : ($announcement->priority === 'medium' ? 'pending' : 'info') }}">
                        {{ ucfirst($announcement->priority) }}
                    </span>
                </div>
            @empty
                <div class="ds-empty-state ds-empty-state--sm">
                    <div class="ds-empty-state-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </div>
                    <p class="ds-empty-state-message">No announcements</p>
                </div>
            @endforelse
        </x-dashboard.widget-card>

        {{-- Upcoming Events --}}
        <x-dashboard.widget-card title="Upcoming Events">
            @forelse($upcomingEvents as $event)
                <div class="ds-event-item">
                    <div class="ds-event-date">
                        <span class="ds-event-day">{{ $event->event_date->format('d') }}</span>
                        <span class="ds-event-month">{{ $event->event_date->format('M') }}</span>
                    </div>
                    <div class="ds-event-info">
                        <p class="ds-event-title">{{ $event->title }}</p>
                        <p class="ds-event-meta">{{ $event->event_date->format('l, M d') }}</p>
                    </div>
                </div>
            @empty
                <div class="ds-empty-state ds-empty-state--sm">
                    <div class="ds-empty-state-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <p class="ds-empty-state-message">No upcoming events</p>
                </div>
            @endforelse
        </x-dashboard.widget-card>
    </div>

    {{-- Charts Section --}}
    @if(!empty($chartData) && count($chartData['attendance_trend_labels'] ?? []) > 0)
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-lg-4">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Attendance Trend</h3>
                        <p class="ds-chart-card-subtitle">Monthly rate (last 6 months)</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 240px;">
                    <canvas id="parentChartAttendance"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Fee Breakdown</h3>
                        <p class="ds-chart-card-subtitle">Paid vs Outstanding</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 240px;">
                    <canvas id="parentChartFees"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Children Performance</h3>
                        <p class="ds-chart-card-subtitle">Average scores by child</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 240px;">
                    <canvas id="parentChartResults"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

@endif

@push('scripts')
<script>
    (function() {
        function init() {
            var attLabels = @json($chartData['attendance_trend_labels'] ?? []);
            var attData = @json($chartData['attendance_trend_data'] ?? []);
            if (attLabels.length) {
                window.SkulCharts.createAreaChart('parentChartAttendance', {
                    labels: attLabels,
                    datasets: [{
                        label: 'Attendance %',
                        data: attData,
                        color: '#8B5CF6',
                        backgroundColor: '#8B5CF618',
                    }],
                    options: {
                        scales: {
                            y: { min: 0, max: 100, ticks: { callback: function(v) { return v + '%'; } } },
                        },
                    },
                });
            }

            var feeLabels = @json($chartData['fee_labels'] ?? []);
            var feeData = @json($chartData['fee_data'] ?? []);
            if (feeLabels.length && feeData.some(function(v) { return v > 0; })) {
                window.SkulCharts.createDoughnutChart('parentChartFees', {
                    labels: feeLabels,
                    data: feeData,
                    colors: ['#10B981', '#F59E0B'],
                });
            }

            var childLabels = @json($chartData['results_child_labels'] ?? []);
            var childData = @json($chartData['results_child_data'] ?? []);
            if (childLabels.length) {
                window.SkulCharts.createBarChart('parentChartResults', {
                    labels: childLabels,
                    datasets: [{
                        label: 'Average Score',
                        data: childData,
                        backgroundColor: '#10B981',
                        borderRadius: 6,
                    }],
                    options: {
                        scales: {
                            y: { min: 0, max: 100, ticks: { callback: function(v) { return v + '%'; } } },
                        },
                    },
                });
            }
        }
        if (!window.SkulCharts) { window.__skulChartsQueue.push(init); return; }
        init();
    })();
</script>
@endpush
@endsection
