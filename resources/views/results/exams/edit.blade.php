@extends('layouts.app')

@section('title', 'Edit Exam - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Exam</h2>
            <p class="text-muted mb-0">{{ $exam->name }}</p>
        </div>
        <a href="{{ route('results.exams.index') }}" class="sb-btn sb-btn-ghost">Back to List</a>
    </div>

    @if($errors->any())
        <div class="sb-alert sb-alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card stat-card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('results.exams.update', $exam) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">School *</label>
                        <select name="school_id" required class="sb-form-select">
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $exam->school_id) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $exam->name) }}" required class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Term</label>
                        <input type="text" name="term" value="{{ old('term', $exam->term) }}" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Session</label>
                        <input type="text" name="session" value="{{ old('session', $exam->session) }}" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $exam->start_date?->format('Y-m-d')) }}" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $exam->end_date?->format('Y-m-d')) }}" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Status *</label>
                        <select name="status" required class="sb-form-select">
                            <option value="1" {{ old('status', $exam->status) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('status', $exam->status) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('results.exams.index') }}" class="sb-btn sb-btn-ghost">Cancel</a>
                    <button type="submit" class="sb-btn sb-btn-primary">Update Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection