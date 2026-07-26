@extends('layouts.app')

@section('title', $subject->name . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $subject->name }}</h2>
            <p class="text-muted mb-0">Subject details and assigned classes</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('subjects.edit', $subject) }}" class="sb-btn sb-btn-outline-primary">
                Edit Subject
            </a>
            <a href="{{ route('subjects.index') }}" class="sb-btn sb-btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Subject Information</h5>

                    <div class="mb-3">
                        <label class="sb-form-label">Name</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500; color: #333;">{{ $subject->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Code</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            @if($subject->code)
                                <code>{{ $subject->code }}</code>
                            @else
                                <span style="color: #6c757d;">Not set</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">School</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">{{ $subject->school->name ?? '—' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Status</label>
                        <p style="margin: 0;">
                            @if($subject->status)
                                <span class="sb-badge sb-badge-active">Active</span>
                            @else
                                <span class="sb-badge sb-badge-inactive">Inactive</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Description</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">{{ $subject->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="d-flex gap-2" style="border-top: 1px solid #e9ecef; padding-top: 16px; margin-top: 8px;">
                        <span style="font-size: 12px; color: #6c757d;">Created: {{ $subject->created_at->format('d M Y, g:i A') }}</span>
                        <span style="font-size: 12px; color: #6c757d;">|</span>
                        <span style="font-size: 12px; color: #6c757d;">Updated: {{ $subject->updated_at->format('d M Y, g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 style="font-weight: 600; margin-bottom: 0; color: #1a1a2e;">Assigned Classes</h5>
                        <span class="sb-badge sb-badge-info">
                            {{ $subject->schoolClasses->count() }} {{ Str::plural('class', $subject->schoolClasses->count()) }}
                        </span>
                    </div>

                    @if($subject->schoolClasses->count())
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @foreach($subject->schoolClasses as $class)
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                                    <div>
                                        <span style="font-weight: 500; font-size: 14px; color: #333;">{{ $class->name }}</span>
                                        @if($class->section)
                                            <span style="color: #6c757d; font-size: 13px;"> - {{ $class->section }}</span>
                                        @endif
                                        @if($class->school)
                                            <div style="font-size: 12px; color: #6c757d; margin-top: 2px;">{{ $class->school->name }}</div>
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
                            <p style="margin: 0; font-size: 14px;">No classes assigned to this subject yet.</p>
                            <a href="{{ route('subjects.edit', $subject) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary mt-2">Assign classes</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
