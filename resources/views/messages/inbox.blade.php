@extends('layouts.app')

@section('title', 'Inbox - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Messages</h2>
            <p class="text-muted mb-0">Your inbox</p>
        </div>
        <a href="{{ route('messages.create') }}" class="sb-btn sb-btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Compose
        </a>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('messages.inbox') }}" class="sb-btn sb-btn-primary">Inbox</a>
        <a href="{{ route('messages.sent') }}" class="sb-btn sb-btn-secondary">Sent</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('messages.inbox') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="sb-form-label">Search by Subject</label>
                    <input type="text" name="search" class="sb-form-input" value="{{ request('search') }}" placeholder="Search messages...">
                </div>
                <div class="col-6 col-md-2">
                    <label class="sb-form-label">Status</label>
                    <select name="status" class="sb-form-select">
                        <option value="">All</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="col-6 col-md-1">
                    <button type="submit" class="sb-btn sb-btn-primary w-100">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'status']))
                    <div class="col-6 col-md-1">
                        <a href="{{ route('messages.inbox') }}" class="sb-btn sb-btn-secondary w-100 text-center">Clear</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover sb-table">
                    <thead>
                        <tr>
                            <th style="width: 30px;"></th>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr {{ $message->status === 'unread' ? 'style="background: #f8f9ff;"' : '' }}>
                                <td>
                                    @if($message->status === 'unread')
                                        <span style="display: inline-block; width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></span>
                                    @endif
                                </td>
                                <td {{ $message->status === 'unread' ? 'style="font-weight: 600;"' : '' }}>
                                    {{ $message->sender->name ?? 'Unknown' }}
                                </td>
                                <td {{ $message->status === 'unread' ? 'style="font-weight: 600;"' : '' }}>
                                    <a href="{{ route('messages.show', $message) }}" style="color: #0a1628; text-decoration: none;">{{ $message->subject }}</a>
                                    @if($message->attachment)
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-left: 4px;">
                                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                        </svg>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    {{ $message->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    @if($message->status === 'unread')
                                        <span class="sb-badge sb-badge-unread">Unread</span>
                                    @else
                                        <span class="sb-badge sb-badge-read">Read</span>
                                    @endif
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <div class="table-actions">
                                        <a href="{{ route('messages.show', $message) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                        <form action="{{ route('messages.destroy', $message) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No messages in your inbox. <a href="{{ route('messages.create') }}" style="color: var(--primary);">Send a message</a>.
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
