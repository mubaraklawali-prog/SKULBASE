@extends('layouts.app')

@section('title', 'Add Class - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Add Class</h2>
        <p class="text-muted mb-0">Create a new class</p>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('classes.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="school_id" class="sb-form-label">School <span class="required">*</span></label>
                        <select
                            name="school_id"
                            id="school_id"
                            class="sb-form-select @error('school_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="name" class="sb-form-label">Class Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. JSS 1, Grade 5"
                            class="sb-form-input @error('name') is-invalid @enderror"
                            required
                        >
                        @error('name')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="section" class="sb-form-label">Section</label>
                        <input
                            type="text"
                            name="section"
                            id="section"
                            value="{{ old('section') }}"
                            placeholder="e.g. A, B, Gold"
                            class="sb-form-input @error('section') is-invalid @enderror"
                        >
                        @error('section')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="sb-form-label">Status <span class="required">*</span></label>
                        <select
                            name="status"
                            id="status"
                            class="sb-form-select @error('status') is-invalid @enderror"
                            required
                        >
                            <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="sb-form-label">Description</label>
                        <textarea
                            name="description"
                            id="description"
                            placeholder="Brief description about this class"
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
                        Save Class
                    </button>
                    <a href="{{ route('classes.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
