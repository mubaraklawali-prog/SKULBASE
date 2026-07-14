@extends('layouts.app')

@section('title', 'Announcements - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Announcements</h2>
            <p class="text-muted mb-0">Notice board & school-wide announcements</p>
        </div>
        @if($canManage)
            <a href="{{ route('announcements.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Announcement
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background: #d1e7dd; border-color: #badbcc; color: #0f5132;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('announcements.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by title..." style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Audience</label>
                    <select name="audience" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Audiences</option>
                        <option value="everyone" {{ request('audience') == 'everyone' ? 'selected' : '' }}>Everyone</option>
                        <option value="teachers" {{ request('audience') == 'teachers' ? 'selected' : '' }}>Teachers</option>
                        <option value="students" {{ request('audience') == 'students' ? 'selected' : '' }}>Students</option>
                        <option value="parents" {{ request('audience') == 'parents' ? 'selected' : '' }}>Parents</option>
                    </select>
                </div>
                @if($canManage)
                    <div class="col-md-2">
                        <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Status</label>
                        <select name="status" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            <option value="">All Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                @endif
                <div class="col-md-1">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer; width: 100%;">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'audience', 'status']))
                    <div class="col-md-1">
                        <a href="{{ route('announcements.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 16px; font-weight: 500; text-decoration: none; width: 100%; text-align: center;">Clear</a>
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
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Title</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Audience</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Created By</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Expires</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Status</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 12px;">
                                    <a href="{{ route('announcements.show', $announcement) }}" style="color: #0a1628; font-weight: 600; text-decoration: none;">{{ $announcement->title }}</a>
                                    @if($announcement->attachment)
                                        <br><small style="color: #6c757d;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                            </svg>
                                            Attachment
                                        </small>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize;">{{ $announcement->audience }}</span>
                                </td>
                                <td style="padding: 12px;">{{ $announcement->creator->name ?? '—' }}</td>
                                <td style="padding: 12px;">
                                    @if($announcement->expires_at)
                                        <span style="font-size: 13px; {{ $announcement->expires_at->isPast() ? 'color: #dc3545;' : 'color: #6c757d;' }}">
                                            {{ $announcement->expires_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span style="color: #adb5bd;">Never</span>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    @if($announcement->status === 'published')
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Published</span>
                                    @else
                                        <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Draft</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: right; white-space: nowrap;">
                                    <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; text-decoration: none;">View</a>
                                    @if($canManage)
                                        <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm" style="background: #f0f2f5; color: #333; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; text-decoration: none;">Edit</a>
                                        <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #dc3545; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; border: none; cursor: pointer;">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No announcements found.
                                    @if($canManage)
                                        <a href="{{ route('announcements.create') }}" style="color: #4f9cf7;">Create your first announcement</a>.
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
