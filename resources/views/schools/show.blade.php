@extends('layouts.app')

@section('title', $school->name . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>{{ $school->name }}</h2>
            <p class="text-muted mb-0">School details and overview</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('schools.edit', $school) }}" class="sb-btn sb-btn-primary">
                Edit School
            </a>
            <a href="{{ route('schools.index') }}" class="sb-btn sb-btn-secondary">
                Back to Schools
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card" style="text-align: center;">
                <div class="card-body">
                    <p style="font-size: 28px; font-weight: 700; color: #1a73e8; margin: 0;">{{ number_format($school->students_count) }}</p>
                    <p style="font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin: 4px 0 0 0;">Students</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card" style="text-align: center;">
                <div class="card-body">
                    <p style="font-size: 28px; font-weight: 700; color: #1e8a3e; margin: 0;">{{ number_format($school->teachers_count) }}</p>
                    <p style="font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin: 4px 0 0 0;">Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card" style="text-align: center;">
                <div class="card-body">
                    <p style="font-size: 28px; font-weight: 700; color: #e67e22; margin: 0;">{{ number_format($school->school_classes_count) }}</p>
                    <p style="font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin: 4px 0 0 0;">Classes</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card" style="text-align: center;">
                <div class="card-body">
                    <p style="font-size: 28px; font-weight: 700; color: #6f42c1; margin: 0;">{{ number_format($school->subjects_count) }}</p>
                    <p style="font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin: 4px 0 0 0;">Subjects</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card stat-card mb-4">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">School Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Status</p>
                            @if($school->is_active)
                                <span class="sb-badge sb-badge-active">Active</span>
                            @else
                                <span class="sb-badge sb-badge-inactive">Inactive</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Slug</p>
                            <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $school->slug }}</code>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Email</p>
                            <p style="margin: 0;">{{ $school->email ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Phone</p>
                            <p style="margin: 0;">{{ $school->phone ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">City</p>
                            <p style="margin: 0;">{{ $school->city ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">State</p>
                            <p style="margin: 0;">{{ $school->state ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Country</p>
                            <p style="margin: 0;">{{ $school->country ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Address</p>
                            <p style="margin: 0;">{{ $school->address ?? '—' }}</p>
                        </div>
                        <div class="col-md-12">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Created</p>
                            <p style="margin: 0;">{{ $school->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($recentStudents->isNotEmpty())
                <div class="card stat-card mb-4">
                    <div class="card-body" style="padding: 24px;">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Recent Students</h5>
                        <div class="table-responsive">
                            <table class="table table-hover sb-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Admission No</th>
                                        <th>Class</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentStudents as $student)
                                        <tr>
                                            <td><strong>{{ $student->full_name }}</strong></td>
                                            <td>{{ $student->admission_number ?? '—' }}</td>
                                            <td>{{ $student->schoolClass->name ?? '—' }}</td>
                                            <td>
                                                @if($student->status === 'active')
                                                    <span class="sb-badge sb-badge-active">Active</span>
                                                @else
                                                    <span class="sb-badge sb-badge-inactive">{{ ucfirst($student->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if($recentTeachers->isNotEmpty())
                <div class="card stat-card mb-4">
                    <div class="card-body" style="padding: 24px;">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Recent Teachers</h5>
                        <div class="table-responsive">
                            <table class="table table-hover sb-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTeachers as $teacher)
                                        <tr>
                                            <td><strong>{{ $teacher->first_name }} {{ $teacher->last_name }}</strong></td>
                                            <td>{{ $teacher->user->email ?? '—' }}</td>
                                            <td>{{ $teacher->phone ?? '—' }}</td>
                                            <td>
                                                @if($teacher->status === 'active')
                                                    <span class="sb-badge sb-badge-active">Active</span>
                                                @else
                                                    <span class="sb-badge sb-badge-inactive">{{ ucfirst($teacher->status) }}</span>
                                                @endif
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

        <div class="col-lg-4">
            @if($subscription)
                <div class="card stat-card mb-4">
                    <div class="card-body" style="padding: 24px;">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Subscription</h5>
                        @php $sub = $subscription; @endphp
                        <div class="mb-3">
                            @if($sub->is_trial)
                                <span class="sb-badge" style="background: #fff3cd; color: #856404;">Trial Period</span>
                            @elseif($sub->isActive())
                                <span class="sb-badge sb-badge-active">{{ $sub->plan->name ?? 'Active Plan' }}</span>
                            @elseif($sub->status === 'grace')
                                <span class="sb-badge" style="background: #fff3cd; color: #856404;">Grace Period</span>
                            @else
                                <span class="sb-badge sb-badge-inactive">{{ ucfirst($sub->status) }}</span>
                            @endif
                        </div>
                        @if($sub->plan)
                            <p style="font-size: 13px; color: #333; margin-bottom: 4px;"><strong>Plan:</strong> {{ $sub->plan->name }}</p>
                        @endif
                        @if($sub->expires_at)
                            <p style="font-size: 13px; color: #333; margin-bottom: 4px;"><strong>Expires:</strong> {{ $sub->expires_at->format('M d, Y') }}</p>
                        @endif
                        @if($sub->trial_ends_at)
                            <p style="font-size: 13px; color: #333; margin-bottom: 0;"><strong>Trial Ends:</strong> {{ $sub->trial_ends_at->format('M d, Y') }}</p>
                        @endif
                    </div>
                </div>
            @endif

            @if($schoolSetting)
                <div class="card stat-card mb-4">
                    <div class="card-body" style="padding: 24px;">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Academic Settings</h5>
                        @if($schoolSetting->current_session)
                            <p style="font-size: 13px; color: #333; margin-bottom: 4px;"><strong>Session:</strong> {{ $schoolSetting->current_session }}</p>
                        @endif
                        @if($schoolSetting->current_term)
                            <p style="font-size: 13px; color: #333; margin-bottom: 4px;"><strong>Term:</strong> {{ $schoolSetting->current_term }}</p>
                        @endif
                        @if($schoolSetting->school_open_time && $schoolSetting->school_close_time)
                            <p style="font-size: 13px; color: #333; margin-bottom: 0;"><strong>Hours:</strong> {{ $schoolSetting->school_open_time }} - {{ $schoolSetting->school_close_time }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card stat-card mb-4">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Quick Actions</h5>
                    <a href="{{ route('settings.index', ['school_id' => $school->id]) }}" class="quick-action-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        Manage Settings
                    </a>
                    <a href="{{ route('schools.edit', $school) }}" class="quick-action-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit School
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
