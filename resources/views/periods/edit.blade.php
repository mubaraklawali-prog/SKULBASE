@extends('layouts.app')

@section('title', 'Edit Period - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Edit Period</h2>
        <p class="text-muted mb-0">Update timetable period details</p>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('periods.update', $period) }}">
                @csrf
                @method('PUT')

                @include('periods._form', ['period' => $period])

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 28px; font-weight: 500; border: none; cursor: pointer;">
                        Update Period
                    </button>
                    <a href="{{ route('periods.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 28px; font-weight: 500; text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
