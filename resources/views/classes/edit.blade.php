@extends('layouts.app')

@section('title', 'Edit Class - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Edit Class</h2>
        <p class="text-muted mb-0">Update {{ $schoolClass->name }} details</p>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('classes.update', $schoolClass) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="school_id" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">School <span style="color: #dc3545;">*</span></label>
                        <select
                            name="school_id"
                            id="school_id"
                            class="form-control @error('school_id') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $schoolClass->school_id) == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="name" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Class Name <span style="color: #dc3545;">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $schoolClass->name) }}"
                            placeholder="e.g. JSS 1, Grade 5"
                            class="form-control @error('name') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                        @error('name')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="section" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Section</label>
                        <input
                            type="text"
                            name="section"
                            id="section"
                            value="{{ old('section', $schoolClass->section) }}"
                            placeholder="e.g. A, B, Gold"
                            class="form-control @error('section') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('section')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Status <span style="color: #dc3545;">*</span></label>
                        <select
                            name="status"
                            id="status"
                            class="form-control @error('status') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                            <option value="1" {{ old('status', (int) $schoolClass->status) === 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', (int) $schoolClass->status) === 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Description</label>
                        <textarea
                            name="description"
                            id="description"
                            placeholder="Brief description about this class"
                            class="form-control @error('description') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            rows="3"
                        >{{ old('description', $schoolClass->description) }}</textarea>
                        @error('description')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 28px; font-weight: 500; border: none; cursor: pointer;">
                        Update Class
                    </button>
                    <a href="{{ route('classes.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 28px; font-weight: 500; text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
