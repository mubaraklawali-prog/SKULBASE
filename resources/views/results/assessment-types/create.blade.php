@extends('layouts.app')

@section('title', 'Create Assessment Type - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Create Assessment Type</h2>
            <p class="text-muted mb-0">Define a new assessment category</p>
        </div>
        <a href="{{ route('results.assessment-types.index') }}" class="sb-btn sb-btn-ghost">Back to List</a>
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
            <form method="POST" action="{{ route('results.assessment-types.store') }}">
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
                        <label class="sb-form-label">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Test 1, Assignment, Exam" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Percentage (%) *</label>
                        <input type="number" name="percentage" value="{{ old('percentage') }}" required min="0.01" max="100" step="0.01" placeholder="e.g. 20" class="sb-form-input">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('results.assessment-types.index') }}" class="sb-btn sb-btn-ghost">Cancel</a>
                    <button type="submit" class="sb-btn sb-btn-primary">Create Assessment Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection