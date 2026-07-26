@extends('layouts.app')

@section('title', 'Add Timetable Entry - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Add Timetable Entry</h2>
            <p class="text-muted mb-0">Schedule a new class in the timetable</p>
        </div>
        <a href="{{ route('timetables.index') }}" class="sb-btn sb-btn-outline-secondary">← Back</a>
    </div>

    <div class="sb-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('timetables.store') }}">
                @csrf

                @include('timetables._form')

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="sb-btn sb-btn-primary">Save Entry</button>
                    <a href="{{ route('timetables.index') }}" class="sb-btn sb-btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
