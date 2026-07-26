@extends('layouts.app')

@section('title', 'Add Plan - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Add Plan</h2>
        <p class="text-muted mb-0">Create a new pricing plan</p>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('plans.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="sb-form-label">Plan Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Starter, Standard, Premium"
                            class="sb-form-input @error('name') is-invalid @enderror"
                            required
                        >
                        @error('name')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="slug" class="sb-form-label">Slug</label>
                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            value="{{ old('slug') }}"
                            placeholder="Auto-generated from name if left empty"
                            class="sb-form-input @error('slug') is-invalid @enderror"
                        >
                        @error('slug')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="monthly_price" class="sb-form-label">Monthly Price (NGN) <span class="required">*</span></label>
                        <input
                            type="number"
                            name="monthly_price"
                            id="monthly_price"
                            value="{{ old('monthly_price') }}"
                            placeholder="e.g. 5000"
                            min="0"
                            step="0.01"
                            class="sb-form-input @error('monthly_price') is-invalid @enderror"
                            required
                        >
                        @error('monthly_price')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="yearly_price" class="sb-form-label">Yearly Price (NGN) <span class="required">*</span></label>
                        <input
                            type="number"
                            name="yearly_price"
                            id="yearly_price"
                            value="{{ old('yearly_price') }}"
                            placeholder="e.g. 50000"
                            min="0"
                            step="0.01"
                            class="sb-form-input @error('yearly_price') is-invalid @enderror"
                            required
                        >
                        @error('yearly_price')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="student_limit" class="sb-form-label">Student Limit</label>
                        <input
                            type="number"
                            name="student_limit"
                            id="student_limit"
                            value="{{ old('student_limit') }}"
                            placeholder="e.g. 300"
                            min="1"
                            class="sb-form-input @error('student_limit') is-invalid @enderror"
                        >
                        <small class="text-muted">Leave empty if unlimited</small>
                        @error('student_limit')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="is_unlimited" class="sb-form-label">Unlimited Students</label>
                        <select
                            name="is_unlimited"
                            id="is_unlimited"
                            class="sb-form-select @error('is_unlimited') is-invalid @enderror"
                        >
                            <option value="0" {{ old('is_unlimited', '0') === '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_unlimited') === '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('is_unlimited')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="trial_days" class="sb-form-label">Trial Days <span class="required">*</span></label>
                        <input
                            type="number"
                            name="trial_days"
                            id="trial_days"
                            value="{{ old('trial_days', 30) }}"
                            min="0"
                            max="365"
                            class="sb-form-input @error('trial_days') is-invalid @enderror"
                            required
                        >
                        @error('trial_days')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="is_active" class="sb-form-label">Status <span class="required">*</span></label>
                        <select
                            name="is_active"
                            id="is_active"
                            class="sb-form-select @error('is_active') is-invalid @enderror"
                            required
                        >
                            <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="sort_order" class="sb-form-label">Sort Order</label>
                        <input
                            type="number"
                            name="sort_order"
                            id="sort_order"
                            value="{{ old('sort_order', 0) }}"
                            min="0"
                            class="sb-form-input @error('sort_order') is-invalid @enderror"
                        >
                        <small class="text-muted">Lower values appear first</small>
                        @error('sort_order')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="sb-form-label">Description</label>
                        <textarea
                            name="description"
                            id="description"
                            placeholder="Brief description about this plan"
                            class="sb-form-textarea @error('description') is-invalid @enderror"
                            rows="3"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="sb-btn sb-btn-primary">
                        Save Plan
                    </button>
                    <a href="{{ route('plans.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
