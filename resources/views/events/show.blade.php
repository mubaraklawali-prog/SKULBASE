@extends('layouts.app')

@section('title', $event->title . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $event->title }}</h2>
            <p class="text-muted mb-0">Event Details</p>
        </div>
        <div class="d-flex gap-2">
            @if(in_array(auth()->user()->role, ['super_admin', 'school_admin']))
                <a href="{{ route('events.edit', $event) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit
                </a>
            @endif
            <a href="{{ route('events.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                ← Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Description</h5>
                    <div style="color: #333; line-height: 1.8; white-space: pre-wrap;">{{ $event->description ?? 'No description provided.' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Information</h5>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Event Type</div>
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
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Date</div>
                        <div style="font-weight: 500; {{ $event->event_date->isPast() ? 'color: #dc3545;' : 'color: #333;' }}">
                            {{ $event->event_date->format('l, M d, Y') }}
                            @if($event->event_date->isToday())
                                <small style="color: #4f9cf7; font-weight: 600;">(Today)</small>
                            @elseif($event->event_date->isPast())
                                <small style="color: #dc3545;">(Past)</small>
                            @endif
                        </div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Time</div>
                        <div style="font-weight: 500; color: #333;">
                            @if($event->start_time && $event->end_time)
                                {{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}
                            @elseif($event->start_time)
                                {{ $event->start_time->format('h:i A') }} onwards
                            @else
                                All Day
                            @endif
                        </div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Location</div>
                        <div style="font-weight: 500; color: #333;">{{ $event->location ?? 'Not specified' }}</div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Status</div>
                        @if($event->status === 'published')
                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Published</span>
                        @else
                            <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Draft</span>
                        @endif
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Created By</div>
                        <div style="font-weight: 500; color: #333;">{{ $event->creator->name ?? '—' }}</div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Created</div>
                        <div style="font-weight: 500; color: #333;">{{ $event->created_at->format('M d, Y \a\t h:i A') }}</div>
                    </div>
                </div>
            </div>

            @if(in_array(auth()->user()->role, ['super_admin', 'school_admin']))
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('events.edit', $event) }}" class="btn w-100" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none; text-align: center;">
                        Edit Event
                    </a>
                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn w-100" style="background: #f8d7da; color: #dc3545; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer;">
                            Delete Event
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
