@extends('layouts.app')

@section('title', 'Period Details - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>{{ $period->name }}</h2>
            <p class="text-muted mb-0">Period details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('periods.edit', $period) }}" class="sb-btn sb-btn-outline-primary">Edit</a>
            <a href="{{ route('periods.index') }}" class="sb-btn sb-btn-outline-secondary">← Back to Periods</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="sb-card">
                <div class="card-body" style="padding: 32px;">
                    <h5 class="fw-semibold mb-3">Basic Information</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Name</label>
                        <p class="mb-0 fw-medium">{{ $period->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Type</label>
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
                        <span class="badge" style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; text-transform: capitalize;">
                            {{ $period->type }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">School</label>
                        <p class="mb-0">{{ $period->school->name ?? '—' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Status</label>
                        @if($period->status)
                            <span class="badge" style="background: #d1e7dd; color: #0f5132;">Active</span>
                        @else
                            <span class="badge" style="background: #f8d7da; color: #842029;">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="sb-card">
                <div class="card-body" style="padding: 32px;">
                    <h5 class="fw-semibold mb-3">Schedule</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Start Time</label>
                        <p class="mb-0 fw-medium">{{ $period->start_time->format('h:i A') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">End Time</label>
                        <p class="mb-0 fw-medium">{{ $period->end_time->format('h:i A') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Duration</label>
                        <p class="mb-0 fw-medium">{{ $period->duration_minutes }} minutes</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Sort Order</label>
                        <p class="mb-0 fw-medium">{{ $period->sort_order }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
