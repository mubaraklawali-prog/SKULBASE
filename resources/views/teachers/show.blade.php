@extends('layouts.app')

@section('title', $teacher->full_name . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $teacher->full_name }}</h2>
            <p class="text-muted mb-0">Teacher profile and assignments</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('teachers.edit', $teacher) }}" class="sb-btn sb-btn-outline-primary">
                Edit Teacher
            </a>
            <a href="{{ route('teachers.index') }}" class="sb-btn sb-btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px; text-align: center;">
                    <div style="width: 96px; height: 96px; border-radius: 50%; overflow: hidden; margin: 0 auto 16px; background: #e7f1ff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 28px; color: #0d6efd;">
                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                    </div>
                    <h5 style="font-weight: 600; margin-bottom: 4px;">{{ $teacher->full_name }}</h5>
                    <p style="color: #6c757d; font-size: 14px; margin-bottom: 12px;">{{ $teacher->school->name ?? '—' }}</p>
                    <span class="sb-badge {{ $teacher->status ? 'sb-badge-active' : 'sb-badge-inactive' }}">
                        {{ $teacher->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Personal Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">First Name</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->first_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">Last Name</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->last_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">Other Name</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->other_name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">Gender</label>
                            <p style="margin: 0; font-size: 15px; color: #333; text-transform: capitalize;">{{ $teacher->gender }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">Email</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->email ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">Phone</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->phone }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="sb-form-label">Address</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->address ?? '—' }}</p>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #e9ecef; padding-top: 16px; margin-top: 8px;">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="sb-form-label">Qualification</label>
                                <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->qualification ?? '—' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="sb-form-label">Employment Date</label>
                                <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->employment_date ? $teacher->employment_date->format('d M Y') : '—' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="sb-form-label">Joined</label>
                                <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #e9ecef; padding-top: 16px; margin-top: 8px;">
                        <h6 style="font-weight: 600; color: #1a1a2e; margin-bottom: 12px;">Permissions</h6>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <span class="sb-badge {{ $teacher->can_mark_attendance ? 'sb-badge-active' : 'sb-badge-inactive' }}">
                                Attendance: {{ $teacher->can_mark_attendance ? 'Granted' : 'Denied' }}
                            </span>
                            <span class="sb-badge {{ $teacher->user ? 'sb-badge-active' : 'sb-badge-inactive' }}">
                                Login Account: {{ $teacher->user ? 'Active' : 'None' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 style="font-weight: 600; margin-bottom: 0; color: #1a1a2e;">Assigned Subjects</h5>
                        <span class="sb-badge sb-badge-info">
                            {{ $teacher->subjects->count() }} {{ Str::plural('subject', $teacher->subjects->count()) }}
                        </span>
                    </div>

                    @if($teacher->subjects->count())
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @foreach($teacher->subjects as $subject)
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                                    <div>
                                        <span style="font-weight: 500; font-size: 14px; color: #333;">{{ $subject->name }}</span>
                                        @if($subject->code)
                                            <code>{{ $subject->code }}</code>
                                        @endif
                                    </div>
                                    <span class="sb-badge {{ $subject->status ? 'sb-badge-active' : 'sb-badge-inactive' }}">
                                        {{ $subject->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                            <p style="margin: 0; font-size: 14px;">No subjects assigned yet.</p>
                            <a href="{{ route('teachers.edit', $teacher) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary mt-2">Assign subjects</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 style="font-weight: 600; margin-bottom: 0; color: #1a1a2e;">Assigned Classes</h5>
                        <span class="sb-badge sb-badge-info">
                            {{ $teacher->schoolClasses->count() }} {{ Str::plural('class', $teacher->schoolClasses->count()) }}
                        </span>
                    </div>

                    @if($teacher->schoolClasses->count())
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @foreach($teacher->schoolClasses as $class)
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                                    <div>
                                        <span style="font-weight: 500; font-size: 14px; color: #333;">{{ $class->name }}</span>
                                        @if($class->section)
                                            <span style="color: #6c757d; font-size: 13px;"> - {{ $class->section }}</span>
                                        @endif
                                    </div>
                                    <span class="sb-badge {{ $class->status ? 'sb-badge-active' : 'sb-badge-inactive' }}">
                                        {{ $class->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                            <p style="margin: 0; font-size: 14px;">No classes assigned yet.</p>
                            <a href="{{ route('teachers.edit', $teacher) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary mt-2">Assign classes</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
