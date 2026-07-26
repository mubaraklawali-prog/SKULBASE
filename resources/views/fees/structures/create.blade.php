@extends('layouts.app')

@section('title', 'Create Fee Structure - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Create Fee Structure</h2>
            <p class="text-muted mb-0">Define a new fee for a class</p>
        </div>
        <a href="{{ route('fees.structures.index') }}" class="sb-btn sb-btn-outline-secondary">← Back to List</a>
    </div>

    @if($errors->any())
        <div class="sb-alert sb-alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="sb-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('fees.structures.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium text-muted small">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Tuition Fee, Exam Fee" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium text-muted small">Class <span class="text-danger">*</span></label>
                        <select name="school_class_id" required class="sb-form-select">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium text-muted small">Amount (&#8358;) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount') }}" required min="0.01" step="0.01" placeholder="0.00" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium text-muted small">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium text-muted small">Term</label>
                        <input type="text" name="term" value="{{ old('term') }}" placeholder="e.g. First Term" class="sb-form-input">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium text-muted small">Session</label>
                        <input type="text" name="session" value="{{ old('session') }}" placeholder="e.g. 2025/2026" class="sb-form-input">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-medium text-muted small">Description</label>
                        <textarea name="description" rows="3" placeholder="Optional description" class="sb-form-input">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('fees.structures.index') }}" class="sb-btn sb-btn-outline-secondary">Cancel</a>
                    <button type="submit" class="sb-btn sb-btn-primary">Create Fee Structure</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
