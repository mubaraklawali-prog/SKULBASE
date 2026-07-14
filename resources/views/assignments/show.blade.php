@extends('layouts.app')

@section('title', $assignment->title . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $assignment->title }}</h2>
            <p class="text-muted mb-0">Assignment Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('assignments.edit', $assignment) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('assignments.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                ← Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Description</h5>
                    <p style="color: #333; line-height: 1.7;">
                        {{ $assignment->description ?? 'No description provided.' }}
                    </p>
                </div>
            </div>

            @if($assignment->instructions)
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Instructions</h5>
                        <div style="color: #333; line-height: 1.7; white-space: pre-wrap;">{{ $assignment->instructions }}</div>
                    </div>
                </div>
            @endif

            @if($assignment->attachment)
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Attachment</h5>
                        <a href="{{ $assignment->attachment_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #f0f7ff; border: 1px solid #e7f1ff; border-radius: 8px; padding: 12px 20px; text-decoration: none; color: #0d6efd; font-weight: 500;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                            </svg>
                            {{ basename($assignment->attachment) }}
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Information</h5>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Status</div>
                        @if($assignment->status === 'published')
                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Published</span>
                        @else
                            <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Draft</span>
                        @endif
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Teacher</div>
                        <div style="font-weight: 500; color: #333;">{{ $assignment->teacher->full_name ?? '—' }}</div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Class</div>
                        <div style="font-weight: 500; color: #333;">{{ $assignment->schoolClass->name ?? '—' }}</div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Subject</div>
                        <div style="font-weight: 500; color: #333;">{{ $assignment->subject->name ?? '—' }}</div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Total Marks</div>
                        <div style="font-weight: 500; color: #333;">{{ $assignment->total_marks ?? '—' }}</div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Due Date</div>
                        <div style="font-weight: 500; {{ $assignment->due_date->isPast() ? 'color: #dc3545;' : 'color: #333;' }}">
                            {{ $assignment->due_date->format('M d, Y') }}
                            @if($assignment->due_date->isPast())
                                <small style="color: #dc3545;">(Overdue)</small>
                            @endif
                        </div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Created</div>
                        <div style="font-weight: 500; color: #333;">{{ $assignment->created_at->format('M d, Y \a\t h:i A') }}</div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-2">
                <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn w-100" style="background: #f8d7da; color: #dc3545; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer;">
                        Delete Assignment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
