@extends('layouts.app')

@section('title', 'Edit Timetable Entry - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Edit Timetable Entry</h2>
            <p class="text-muted mb-0">Update timetable schedule details</p>
        </div>
        <a href="{{ route('timetables.index') }}" class="sb-btn sb-btn-outline-secondary">← Back</a>
    </div>

    <div class="sb-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('timetables.update', $timetable) }}">
                @csrf
                @method('PUT')

                @include('timetables._form', ['timetable' => $timetable])

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="sb-btn sb-btn-primary">Update Entry</button>
                    <a href="{{ route('timetables.index') }}" class="sb-btn sb-btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
