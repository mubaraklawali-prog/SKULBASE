@extends('layouts.app')

@section('title', 'Add Period - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Add Period</h2>
            <p class="text-muted mb-0">Create a new timetable period</p>
        </div>
        <a href="{{ route('periods.index') }}" class="sb-btn sb-btn-outline-secondary">← Back</a>
    </div>

    <div class="sb-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('periods.store') }}">
                @csrf

                @include('periods._form')

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="sb-btn sb-btn-primary">Save Period</button>
                    <a href="{{ route('periods.index') }}" class="sb-btn sb-btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
