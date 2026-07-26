@extends('layouts.app')

@section('title', "Assignment Details - Skulbase")

@section('content')
<style>
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-upcoming {
        background: #e7f1ff;
        color: #0d6efd;
    }
    .status-submitted {
        background: #d1e7dd;
        color: #0f5132;
    }
    .status-overdue {
        background: #f8d7da;
        color: #842029;
    }
    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .detail-value {
        font-size: 15px;
        color: #0a1628;
        font-weight: 500;
    }
    .detail-divider {
        border-top: 1px solid #e2e8f0;
        margin: 16px 0;
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Assignment Details</h2>
            <p class="text-muted mb-0">Viewing assignment for {{ $assignment->student->full_name ?? '' }}</p>
        </div>
        <a href="{{ route('parent.assignments.index', ['student_id' => $assignment->student_id ?? '']) }}" class="sb-btn sb-btn-outline-secondary d-inline-flex align-items-center gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Assignments
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <h4 style="font-weight: 700; color: #0a1628; margin-bottom: 8px;">{{ $assignment->title }}</h4>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            {{ $assignment->subject->name ?? '—' }}
                        </span>
                        @php
                            $status = strtolower($assignment->status ?? 'upcoming');
                            $statusClass = match($status) {
                                'submitted' => 'status-submitted',
                                'overdue' => 'status-overdue',
                                default => 'status-upcoming',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="detail-label">Teacher</div>
                    <div class="detail-value">{{ $assignment->teacher->full_name ?? '—' }}</div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="detail-label">Class</div>
                    <div class="detail-value">{{ $assignment->schoolClass->name ?? '—' }}</div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="detail-label">Due Date</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="detail-label">Total Marks</div>
                    <div class="detail-value">{{ $assignment->total_marks ?? '—' }}</div>
                </div>
            </div>

            @if($assignment->description)
                <div class="detail-divider"></div>
                <div class="detail-label">Description</div>
                <div class="detail-value" style="white-space: pre-wrap;">{{ $assignment->description }}</div>
            @endif

            @if($assignment->instructions)
                <div class="detail-divider"></div>
                <div class="detail-label">Instructions</div>
                <div class="detail-value" style="white-space: pre-wrap;">{{ $assignment->instructions }}</div>
            @endif

            @if($assignment->attachment_url)
                <div class="detail-divider"></div>
                <div class="detail-label">Attachment</div>
                <a href="{{ $assignment->attachment_url }}" target="_blank" class="sb-btn sb-btn-outline-primary d-inline-flex align-items-center gap-2 mt-1">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                    </svg>
                    Download Attachment
                </a>
            @endif
        </div>
    </div>
</div>
@endsection