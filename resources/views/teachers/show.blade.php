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
            <a href="{{ route('teachers.edit', $teacher) }}" class="btn" style="background: #e7f1ff; color: #0d6efd; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                Edit Teacher
            </a>
            <a href="{{ route('teachers.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
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
                    <span style="background: {{ $teacher->status ? '#d1e7dd' : '#f8d7da' }}; color: {{ $teacher->status ? '#0f5132' : '#842029' }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
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
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">First Name</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->first_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Last Name</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->last_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Other Name</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->other_name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Gender</label>
                            <p style="margin: 0; font-size: 15px; color: #333; text-transform: capitalize;">{{ $teacher->gender }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Email</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->email ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Phone</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->phone }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Address</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->address ?? '—' }}</p>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #e9ecef; padding-top: 16px; margin-top: 8px;">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Qualification</label>
                                <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->qualification ?? '—' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Employment Date</label>
                                <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->employment_date ? $teacher->employment_date->format('d M Y') : '—' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Joined</label>
                                <p style="margin: 0; font-size: 15px; color: #333;">{{ $teacher->created_at->format('d M Y') }}</p>
                            </div>
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
                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
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
                                            <code style="background: #e7f1ff; padding: 1px 6px; border-radius: 4px; font-size: 11px; color: #0d6efd; margin-left: 6px;">{{ $subject->code }}</code>
                                        @endif
                                    </div>
                                    <span style="background: {{ $subject->status ? '#d1e7dd' : '#f8d7da' }}; color: {{ $subject->status ? '#0f5132' : '#842029' }}; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                        {{ $subject->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                            <p style="margin: 0; font-size: 14px;">No subjects assigned yet.</p>
                            <a href="{{ route('teachers.edit', $teacher) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">Assign subjects</a>
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
                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
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
                                    <span style="background: {{ $class->status ? '#d1e7dd' : '#f8d7da' }}; color: {{ $class->status ? '#0f5132' : '#842029' }}; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                        {{ $class->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                            <p style="margin: 0; font-size: 14px;">No classes assigned yet.</p>
                            <a href="{{ route('teachers.edit', $teacher) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">Assign classes</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
