@extends('layouts.app')

@section('title', 'Edit Event - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Event</h2>
            <p class="text-muted mb-0">Update: {{ $event->title }}</p>
        </div>
        <a href="{{ route('events.index') }}" class="sb-btn sb-btn-secondary">
            ← Back to Events
        </a>
    </div>

    <form method="POST" action="{{ route('events.update', $event) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Event Details</h5>

                        <div class="mb-3">
                            <label class="sb-form-label">Title <span class="required">*</span></label>
                            <input type="text" name="title" class="sb-form-input @error('title') is-invalid @enderror" value="{{ old('title', $event->title) }}" required>
                            @error('title')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Description</label>
                            <textarea name="description" class="sb-form-textarea @error('description') is-invalid @enderror" rows="6">{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Location</label>
                            <input type="text" name="location" class="sb-form-input @error('location') is-invalid @enderror" value="{{ old('location', $event->location) }}">
                            @error('location')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Schedule</h5>

                        <div class="mb-3">
                            <label class="sb-form-label">Event Date <span class="required">*</span></label>
                            <input type="date" name="event_date" class="sb-form-input @error('event_date') is-invalid @enderror" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                            @error('event_date')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Start Time</label>
                            <input type="time" name="start_time" class="sb-form-input @error('start_time') is-invalid @enderror" value="{{ old('start_time', $event->start_time?->format('H:i')) }}">
                            @error('start_time')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">End Time</label>
                            <input type="time" name="end_time" class="sb-form-input @error('end_time') is-invalid @enderror" value="{{ old('end_time', $event->end_time?->format('H:i')) }}">
                            @error('end_time')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Classification</h5>

                        <div class="mb-3">
                            <label class="sb-form-label">Event Type <span class="required">*</span></label>
                            <select name="event_type" class="sb-form-select @error('event_type') is-invalid @enderror" required>
                                @foreach(['academic' => 'Academic', 'exam' => 'Exam', 'holiday' => 'Holiday', 'meeting' => 'Meeting', 'sports' => 'Sports', 'other' => 'Other'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('event_type', $event->event_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('event_type')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Status</label>
                            <select name="status" class="sb-form-select @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="sb-btn sb-btn-primary" style="flex: 1;">
                        Update Event
                    </button>
                    <a href="{{ route('events.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
