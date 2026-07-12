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
            <a href="{{ route('subjects.edit', $subject) }}" class="btn" style="background: #e7f1ff; color: #0d6efd; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                Edit Subject
            </a>
            <a href="{{ route('subjects.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
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
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Name</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500; color: #333;">{{ $subject->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Code</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            @if($subject->code)
                                <code style="background: #f0f2f5; padding: 4px 10px; border-radius: 4px; font-size: 14px;">{{ $subject->code }}</code>
                            @else
                                <span style="color: #6c757d;">Not set</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">School</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">{{ $subject->school->name ?? '—' }}</p>
                    </div>

                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Status</label>
                        <p style="margin: 0;">
                            @if($subject->status)
                                <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                            @else
                                <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Description</label>
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
                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
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
                                    <span style="background: {{ $class->status ? '#d1e7dd' : '#f8d7da' }}; color: {{ $class->status ? '#0f5132' : '#842029' }}; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                        {{ $class->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                            <p style="margin: 0; font-size: 14px;">No classes assigned to this subject yet.</p>
                            <a href="{{ route('subjects.edit', $subject) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">Assign classes</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
