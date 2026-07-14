@extends('layouts.app')

@section('title', 'Create Event - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Create Event</h2>
            <p class="text-muted mb-0">Add a new event to the school calendar</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            ← Back to Events
        </a>
    </div>

    <form method="POST" action="{{ route('events.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Event Details</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Title <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Parent-Teacher Conference" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('title')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Description</label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Describe the event..." style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">{{ old('description') }}</textarea>
                            @error('description')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Location</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g. Main Hall, Room 101" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
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
                            <input type="date" name="event_date" class="form-control" value="{{ old('event_date') }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('event_date')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Start Time</label>
                            <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('start_time')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">End Time</label>
                            <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
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
                                <option value="academic" {{ old('event_type') === 'academic' ? 'selected' : '' }}>Academic</option>
                                <option value="exam" {{ old('event_type') === 'exam' ? 'selected' : '' }}>Exam</option>
                                <option value="holiday" {{ old('event_type') === 'holiday' ? 'selected' : '' }}>Holiday</option>
                                <option value="meeting" {{ old('event_type') === 'meeting' ? 'selected' : '' }}>Meeting</option>
                                <option value="sports" {{ old('event_type') === 'sports' ? 'selected' : '' }}>Sports</option>
                                <option value="other" {{ old('event_type') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('event_type')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Status</label>
                            <select name="status" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer; flex: 1;">
                        Create Event
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
