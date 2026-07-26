@extends('layouts.app')

@section('title', 'My Profile - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>My Profile</h2>
            <p class="text-muted mb-0">Your teaching profile and assignments</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card">
                <div class="card-body" style="padding: 24px; text-align: center;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: #e7f1ff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 28px; color: #0d6efd; margin: 0 auto 16px;">
                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                    </div>
                    <h4 style="font-weight: 600; margin-bottom: 4px;">{{ $teacher->full_name }}</h4>
                    <p class="text-muted" style="font-size: 14px; margin-bottom: 16px;">{{ $teacher->email }}</p>

                    <div style="text-align: left; border-top: 1px solid #e9ecef; padding-top: 16px;">
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Phone</span>
                            <span style="font-size: 13px; font-weight: 500;">{{ $teacher->phone ?: 'N/A' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Gender</span>
                            <span style="font-size: 13px; font-weight: 500; text-transform: capitalize;">{{ $teacher->gender }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Qualification</span>
                            <span style="font-size: 13px; font-weight: 500;">{{ $teacher->qualification ?: 'N/A' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Employment Date</span>
                            <span style="font-size: 13px; font-weight: 500;">{{ $teacher->employment_date?->format('M d, Y') ?: 'N/A' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                            <span class="text-muted" style="font-size: 13px;">Can Mark Attendance</span>
                            <span style="font-size: 13px; font-weight: 500;">
                                @if($teacher->can_mark_attendance)
                                    <span style="color: #198754;">Yes</span>
                                @else
                                    <span style="color: #6c757d;">No</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card stat-card mb-4">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 16px;">Assigned Classes</h5>
                    @forelse($teacher->schoolClasses as $class)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 8px;">
                            <div>
                                <span style="font-weight: 500; font-size: 14px;">{{ $class->name }}</span>
                                @if($class->section)
                                    <span class="text-muted" style="font-size: 13px;"> - {{ $class->section }}</span>
                                @endif
                            </div>
                            <span class="text-muted" style="font-size: 13px;">{{ $class->students_count }} students</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0" style="font-size: 14px;">No classes assigned.</p>
                    @endforelse
                </div>
            </div>

            <div class="card stat-card">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 16px;">Assigned Subjects</h5>
                    @forelse($teacher->subjects as $subject)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 8px;">
                            <span style="font-weight: 500; font-size: 14px;">{{ $subject->name }}</span>
                            @if($subject->code)
                                <span class="text-muted" style="font-size: 13px;">{{ $subject->code }}</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0" style="font-size: 14px;">No subjects assigned.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
