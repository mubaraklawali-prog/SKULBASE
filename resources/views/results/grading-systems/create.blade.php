@extends('layouts.app')

@section('title', 'Add Grading Rule - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Add Grading Rule</h2>
            <p class="text-muted mb-0">Define a new score range and grade</p>
        </div>
        <a href="{{ route('results.grading-systems.index') }}" class="sb-btn sb-btn-ghost">Back to List</a>
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
            <form method="POST" action="{{ route('results.grading-systems.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">School *</label>
                        <select name="school_id" required class="sb-form-select">
                            <option value="">-- Select School --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Grade *</label>
                        <input type="text" name="grade" value="{{ old('grade') }}" required placeholder="e.g. A+, A, B+, B, C, D, F" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Minimum Score (%) *</label>
                        <input type="number" name="min_score" value="{{ old('min_score') }}" required min="0" max="100" step="0.01" placeholder="0" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Maximum Score (%) *</label>
                        <input type="number" name="max_score" value="{{ old('max_score') }}" required min="0" max="100" step="0.01" placeholder="100" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Grade Point</label>
                        <input type="number" name="grade_point" value="{{ old('grade_point') }}" min="0" step="0.01" placeholder="e.g. 4.0" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Remark *</label>
                        <input type="text" name="remark" value="{{ old('remark') }}" required placeholder="e.g. Excellent, Very Good, Good" class="sb-form-input">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('results.grading-systems.index') }}" class="sb-btn sb-btn-ghost">Cancel</a>
                    <button type="submit" class="sb-btn sb-btn-primary">Create Rule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection