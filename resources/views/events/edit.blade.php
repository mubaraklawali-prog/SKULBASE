@extends('layouts.app')

@section('title', 'Edit Event - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Event</h2>
            <p class="text-muted mb-0">Update: {{ $event->title }}</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
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
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Title <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('title')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Description</label>
                            <textarea name="description" class="form-control" rows="6" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Location</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $event->location) }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('location')
                                <small style="color: #dc3545;">{{ $message }}</small>
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
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Event Date <span style="color: #dc3545;">*</span></label>
                            <input type="date" name="event_date" class="form-control" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('event_date')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Start Time</label>
                            <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $event->start_time?->format('H:i')) }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('start_time')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">End Time</label>
                            <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $event->end_time?->format('H:i')) }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('end_time')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Classification</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Event Type <span style="color: #dc3545;">*</span></label>
                            <select name="event_type" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @foreach(['academic' => 'Academic', 'exam' => 'Exam', 'holiday' => 'Holiday', 'meeting' => 'Meeting', 'sports' => 'Sports', 'other' => 'Other'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('event_type', $event->event_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('event_type')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Status</label>
                            <select name="status" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer; flex: 1;">
                        Update Event
                    </button>
                    <a href="{{ route('events.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
