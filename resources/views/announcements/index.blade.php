@extends('layouts.app')

@section('title', 'Announcements - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Announcements</h2>
            <p class="text-muted mb-0">Notice board & school-wide announcements</p>
        </div>
        @if($canManage)
            <a href="{{ route('announcements.create') }}" class="sb-btn sb-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Announcement
            </a>
        @endif
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('announcements.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="sb-form-label">Search</label>
                    <input type="text" name="search" class="sb-form-input" value="{{ request('search') }}" placeholder="Search by title...">
                </div>
                <div class="col-6 col-md-2">
                    <label class="sb-form-label">Audience</label>
                    <select name="audience" class="sb-form-select">
                        <option value="">All Audiences</option>
                        <option value="everyone" {{ request('audience') == 'everyone' ? 'selected' : '' }}>Everyone</option>
                        <option value="teachers" {{ request('audience') == 'teachers' ? 'selected' : '' }}>Teachers</option>
                        <option value="students" {{ request('audience') == 'students' ? 'selected' : '' }}>Students</option>
                        <option value="parents" {{ request('audience') == 'parents' ? 'selected' : '' }}>Parents</option>
                    </select>
                </div>
                @if($canManage)
                    <div class="col-6 col-md-2">
                        <label class="sb-form-label">Status</label>
                        <select name="status" class="sb-form-select">
                            <option value="">All Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                @endif
                <div class="col-6 col-md-1">
                    <button type="submit" class="sb-btn sb-btn-primary w-100">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'audience', 'status']))
                    <div class="col-6 col-md-1">
                        <a href="{{ route('announcements.index') }}" class="sb-btn sb-btn-secondary w-100 text-center">Clear</a>
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
                            <th>Title</th>
                            <th>Audience</th>
                            <th>Created By</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td>
                                    <a href="{{ route('announcements.show', $announcement) }}" style="color: #0a1628; font-weight: 600; text-decoration: none;">{{ $announcement->title }}</a>
                                    @if($announcement->attachment)
                                        <br><small class="text-muted">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                            </svg>
                                            Attachment
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="sb-badge sb-badge-info" style="text-transform: capitalize;">{{ $announcement->audience }}</span>
                                </td>
                                <td>{{ $announcement->creator->name ?? '—' }}</td>
                                <td>
                                    @if($announcement->expires_at)
                                        <span class="{{ $announcement->expires_at->isPast() ? 'text-danger' : 'text-muted' }}">
                                            {{ $announcement->expires_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>
                                    @if($announcement->status === 'published')
                                        <span class="sb-badge sb-badge-published">Published</span>
                                    @else
                                        <span class="sb-badge sb-badge-draft">Draft</span>
                                    @endif
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <div class="table-actions">
                                        <a href="{{ route('announcements.show', $announcement) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                        @if($canManage)
                                            <a href="{{ route('announcements.edit', $announcement) }}" class="sb-btn sb-btn-sm sb-btn-secondary">Edit</a>
                                            <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No announcements found.
                                    @if($canManage)
                                        <a href="{{ route('announcements.create') }}" style="color: var(--primary);">Create your first announcement</a>.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($announcements->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
