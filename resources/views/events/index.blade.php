@extends('layouts.app')

@section('title', 'Events - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>School Calendar</h2>
            <p class="text-muted mb-0">View and manage school events</p>
        </div>
        @if($canManage)
            <a href="{{ route('events.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Event
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
            <form method="GET" action="{{ route('events.index') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by title..." style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Event Type</label>
                    <select name="event_type" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Types</option>
                        <option value="academic" {{ request('event_type') == 'academic' ? 'selected' : '' }}>Academic</option>
                        <option value="exam" {{ request('event_type') == 'exam' ? 'selected' : '' }}>Exam</option>
                        <option value="holiday" {{ request('event_type') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                        <option value="meeting" {{ request('event_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="sports" {{ request('event_type') == 'sports' ? 'selected' : '' }}>Sports</option>
                        <option value="other" {{ request('event_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                </div>
                @if($canManage)
                    <div class="col-md-1">
                        <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Status</label>
                        <select name="status" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            <option value="">All</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                @endif
                <div class="col-md-1">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer; width: 100%;">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'event_type', 'date_from', 'date_to', 'status']))
                    <div class="col-md-1">
                        <a href="{{ route('events.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 16px; font-weight: 500; text-decoration: none; width: 100%; text-align: center;">Clear</a>
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
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Date</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Title</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Type</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Time</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Location</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Status</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 12px; white-space: nowrap;">
                                    <span style="font-weight: 500; {{ $event->event_date->isPast() ? 'color: #dc3545;' : 'color: #333;' }}">
                                        {{ $event->event_date->format('M d, Y') }}
                                    </span>
                                    @if($event->event_date->isToday())
                                        <br><small style="color: #4f9cf7; font-weight: 600;">Today</small>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    <a href="{{ route('events.show', $event) }}" style="color: #0a1628; font-weight: 600; text-decoration: none;">{{ $event->title }}</a>
                                </td>
                                <td style="padding: 12px;">
                                    @php
                                        $typeColors = [
                                            'academic' => ['bg' => '#e7f1ff', 'text' => '#0d6efd'],
                                            'exam' => ['bg' => '#f8d7da', 'text' => '#dc3545'],
                                            'holiday' => ['bg' => '#d1e7dd', 'text' => '#0f5132'],
                                            'meeting' => ['bg' => '#fff3cd', 'text' => '#664d03'],
                                            'sports' => ['bg' => '#e7f1ff', 'text' => '#0d6efd'],
                                            'other' => ['bg' => '#f0f2f5', 'text' => '#6c757d'],
                                        ];
                                        $color = $typeColors[$event->event_type] ?? $typeColors['other'];
                                    @endphp
                                    <span style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize;">{{ $event->event_type }}</span>
                                </td>
                                <td style="padding: 12px; font-size: 13px; color: #6c757d;">
                                    @if($event->start_time && $event->end_time)
                                        {{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}
                                    @elseif($event->start_time)
                                        {{ $event->start_time->format('h:i A') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td style="padding: 12px; font-size: 13px; color: #6c757d;">
                                    {{ $event->location ?? '—' }}
                                </td>
                                <td style="padding: 12px;">
                                    @if($event->status === 'published')
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Published</span>
                                    @else
                                        <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Draft</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: right; white-space: nowrap;">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; text-decoration: none;">View</a>
                                    @if($canManage)
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm" style="background: #f0f2f5; color: #333; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; text-decoration: none;">Edit</a>
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #dc3545; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; border: none; cursor: pointer;">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No events found.
                                    @if($canManage)
                                        <a href="{{ route('events.create') }}" style="color: #4f9cf7;">Create your first event</a>.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
