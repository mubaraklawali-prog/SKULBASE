@extends('layouts.app')

@section('title', 'Edit Assessment Type - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Assessment Type</h2>
            <p class="text-muted mb-0">{{ $assessmentType->name }}</p>
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
            <form method="POST" action="{{ route('results.assessment-types.update', $assessmentType) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">School *</label>
                        <select name="school_id" required class="sb-form-select">
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $assessmentType->school_id) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $assessmentType->name) }}" required class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Percentage (%) *</label>
                        <input type="number" name="percentage" value="{{ old('percentage', $assessmentType->percentage) }}" required min="0.01" max="100" step="0.01" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Status *</label>
                        <select name="status" required class="sb-form-select">
                            <option value="1" {{ old('status', $assessmentType->status) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('status', $assessmentType->status) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('results.assessment-types.index') }}" class="sb-btn sb-btn-ghost">Cancel</a>
                    <button type="submit" class="sb-btn sb-btn-primary">Update Assessment Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection