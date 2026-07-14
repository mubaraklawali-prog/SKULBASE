@extends('layouts.app')

@section('title', 'Period Details - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $period->name }}</h2>
            <p class="text-muted mb-0">Period details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('periods.edit', $period) }}" class="btn" style="background: #e7f1ff; color: #0d6efd; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                Edit
            </a>
            <a href="{{ route('periods.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                Back to Periods
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card stat-card">
                <div class="card-body" style="padding: 32px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px;">Basic Information</h5>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Name</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $period->name }}</p>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Type</label>
                        @php
                            $typeColors = [
                                'academic' => ['bg' => '#e7f1ff', 'text' => '#0d6efd'],
                                'break' => ['bg' => '#fff3cd', 'text' => '#664d03'],
                                'lunch' => ['bg' => '#d1e7dd', 'text' => '#0f5132'],
                                'assembly' => ['bg' => '#f0d9ff', 'text' => '#6f42c1'],
                                'other' => ['bg' => '#f0f2f5', 'text' => '#6c757d'],
                            ];
                            $colors = $typeColors[$period->type] ?? $typeColors['other'];
                        @endphp
                        <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize;">
                            {{ $period->type }}
                        </span>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">School</label>
                        <p style="margin: 0; font-size: 15px;">{{ $period->school->name ?? '—' }}</p>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Status</label>
                        @if($period->status)
                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                        @else
                            <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card stat-card">
                <div class="card-body" style="padding: 32px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px;">Schedule</h5>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Start Time</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $period->start_time->format('h:i A') }}</p>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">End Time</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $period->end_time->format('h:i A') }}</p>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Duration</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $period->duration_minutes }} minutes</p>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Sort Order</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500;">{{ $period->sort_order }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
