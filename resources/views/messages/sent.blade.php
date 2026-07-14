@extends('layouts.app')

@section('title', 'Sent Messages - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Messages</h2>
            <p class="text-muted mb-0">Sent messages</p>
        </div>
        <a href="{{ route('messages.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Compose
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background: #d1e7dd; border-color: #badbcc; color: #0f5132;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('messages.inbox') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 8px 20px; font-weight: 500; text-decoration: none;">Inbox</a>
        <a href="{{ route('messages.sent') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 8px 20px; font-weight: 500; text-decoration: none;">Sent</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('messages.sent') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Search by Subject</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search messages..." style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer; width: 100%;">Filter</button>
                </div>
                @if(request()->has('search'))
                    <div class="col-md-1">
                        <a href="{{ route('messages.sent') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 16px; font-weight: 500; text-decoration: none; width: 100%; text-align: center;">Clear</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" style="margin-bottom: 0;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">To</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Subject</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Date</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Status</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 12px;">
                                    @if($message->recipient_id)
                                        {{ $message->recipient->name ?? 'Unknown' }}
                                    @elseif($message->recipient_role)
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize;">All {{ $message->recipient_role }}</span>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    <a href="{{ route('messages.show', $message) }}" style="color: #0a1628; font-weight: 500; text-decoration: none;">{{ $message->subject }}</a>
                                    @if($message->attachment)
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-left: 4px;">
                                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                        </svg>
                                    @endif
                                </td>
                                <td style="padding: 12px; font-size: 13px; color: #6c757d;">
                                    {{ $message->created_at->format('M d, Y') }}
                                </td>
                                <td style="padding: 12px;">
                                    @if($message->status === 'unread')
                                        <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Unread</span>
                                    @else
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Read</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: right; white-space: nowrap;">
                                    <a href="{{ route('messages.show', $message) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; text-decoration: none;">View</a>
                                    <form action="{{ route('messages.destroy', $message) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #dc3545; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; border: none; cursor: pointer;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No sent messages. <a href="{{ route('messages.create') }}" style="color: #4f9cf7;">Send your first message</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($messages->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
