@extends('layouts.app')

@section('title', $assignment->title . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $assignment->title }}</h2>
            <p class="text-muted mb-0">{{ $assignment->subject->name ?? '' }}</p>
        </div>
        <a href="{{ route('student.assignments.index') }}" class="sb-btn sb-btn-secondary">
            Back to Assignments
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card stat-card mb-4">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Assignment Details</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Subject</p>
                            <p style="margin: 4px 0 0; font-weight: 500;">{{ $assignment->subject->name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Teacher</p>
                            <p style="margin: 4px 0 0; font-weight: 500;">{{ $assignment->teacher->first_name ?? '' }} {{ $assignment->teacher->last_name ?? '' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Class</p>
                            <p style="margin: 4px 0 0; font-weight: 500;">{{ $assignment->schoolClass->name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Due Date</p>
                            <p style="margin: 4px 0 0; font-weight: 500;">{{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Total Marks</p>
                            <p style="margin: 4px 0 0; font-weight: 500;">{{ $assignment->total_marks ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p style="font-size: 12px; color: #6c757d; margin: 0;">Status</p>
                            @php
                                $isOverdue = $assignment->due_date && $assignment->due_date->isPast();
                            @endphp
                            <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; {{ $isOverdue ? 'background: #f8d7da; color: #842029;' : 'background: #d1e7dd; color: #0f5132;' }}">
                                {{ $isOverdue ? 'Overdue' : 'Upcoming' }}
                            </span>
                        </div>
                    </div>

                    @if($assignment->description)
                        <hr style="margin: 20px 0;">
                        <h6 style="font-weight: 600; margin-bottom: 8px;">Description</h6>
                        <p style="color: #333; line-height: 1.6;">{{ $assignment->description }}</p>
                    @endif

                    @if($assignment->instructions)
                        <hr style="margin: 20px 0;">
                        <h6 style="font-weight: 600; margin-bottom: 8px;">Instructions</h6>
                        <div style="color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $assignment->instructions }}</div>
                    @endif

                    @if($assignment->attachment_url)
                        <hr style="margin: 20px 0;">
                        <h6 style="font-weight: 600; margin-bottom: 8px;">Attachment</h6>
                        <a href="{{ $assignment->attachment_url }}" target="_blank" class="sb-btn sb-btn-outline-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Download Attachment
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card stat-card">
                <div class="card-body" style="padding: 24px;">
                    <h6 style="font-weight: 600; margin-bottom: 12px;">Quick Info</h6>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; background: #e7f1ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0d6efd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size: 11px; color: #6c757d; margin: 0;">Due Date</p>
                                <p style="font-size: 13px; font-weight: 600; margin: 0;">{{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : '—' }}</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; background: #e6f9ed; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e8a3e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size: 11px; color: #6c757d; margin: 0;">Total Marks</p>
                                <p style="font-size: 13px; font-weight: 600; margin: 0;">{{ $assignment->total_marks ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
