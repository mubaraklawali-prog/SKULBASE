@extends('layouts.app')

@section('title', $message->subject . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $message->subject }}</h2>
            <p class="text-muted mb-0">Message Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ $message->sender_id == auth()->id() ? route('messages.sent') : route('messages.inbox') }}" class="sb-btn sb-btn-secondary">
                ← Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <div style="color: #333; line-height: 1.8; white-space: pre-wrap; font-size: 15px;">{{ $message->message }}</div>
                </div>
            </div>

            @if($message->attachment)
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Attachment</h5>
                        <a href="{{ $message->attachment_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #f0f7ff; border: 1px solid #e7f1ff; border-radius: 8px; padding: 12px 20px; text-decoration: none; color: #0d6efd; font-weight: 500;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                            </svg>
                            {{ basename($message->attachment) }}
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
                        <div class="sb-form-label">From</div>
                        <div style="font-weight: 500; color: #333;">{{ $message->sender->name ?? 'Unknown' }}</div>
                        <div style="font-size: 12px; color: #6c757d;">{{ $message->sender->email ?? '' }}</div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div class="sb-form-label">To</div>
                        @if($message->recipient_id)
                            <div style="font-weight: 500; color: #333;">{{ $message->recipient->name ?? 'Unknown' }}</div>
                            <div style="font-size: 12px; color: #6c757d;">{{ $message->recipient->email ?? '' }}</div>
                        @elseif($message->recipient_role)
                            <span class="sb-badge sb-badge-info" style="text-transform: capitalize;">All {{ $message->recipient_role }}</span>
                        @endif
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div class="sb-form-label">Status</div>
                        @if($message->status === 'unread')
                            <span class="sb-badge sb-badge-unread">Unread</span>
                        @else
                            <span class="sb-badge sb-badge-read">Read</span>
                        @endif
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                        <div class="sb-form-label">Sent</div>
                        <div style="font-weight: 500; color: #333;">{{ $message->created_at->format('M d, Y \a\t h:i A') }}</div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-2">
                @if($message->sender_id != auth()->id())
                    <a href="{{ route('messages.create') }}?recipient_id={{ $message->sender_id }}" class="sb-btn sb-btn-primary w-100" style="text-align: center;">
                        Reply
                    </a>
                @endif
                <form action="{{ route('messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="sb-btn sb-btn-outline-danger w-100">
                        Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
