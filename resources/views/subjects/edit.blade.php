@extends('layouts.app')

@section('title', 'Edit Subject - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Edit Subject</h2>
        <p class="text-muted mb-0">Update {{ $subject->name }} details</p>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('subjects.update', $subject) }}">
                @csrf
                @method('PUT')

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
                                <option value="{{ $school->id }}" {{ old('school_id', $subject->school_id) == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="name" class="sb-form-label">Subject Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $subject->name) }}"
                            placeholder="e.g. Mathematics, English"
                            class="sb-form-input @error('name') is-invalid @enderror"
                            required
                        >
                        @error('name')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="code" class="sb-form-label">Code</label>
                        <input
                            type="text"
                            name="code"
                            id="code"
                            value="{{ old('code', $subject->code) }}"
                            placeholder="e.g. MTH, ENG"
                            class="sb-form-input @error('code') is-invalid @enderror"
                        >
                        @error('code')
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
                            <option value="1" {{ old('status', (int) $subject->status) === 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', (int) $subject->status) === 0 ? 'selected' : '' }}>Inactive</option>
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
                            placeholder="Brief description about this subject"
                            class="sb-form-textarea @error('description') is-invalid @enderror"
                            rows="3"
                        >{{ old('description', $subject->description) }}</textarea>
                        @error('description')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="school_classes" class="sb-form-label">Assign Classes</label>
                        <select
                            name="school_classes[]"
                            id="school_classes"
                            class="sb-form-select @error('school_classes') is-invalid @enderror"
                            multiple
                            size="5"
                        >
                            @foreach($schoolClasses as $class)
                                <option value="{{ $class->id }}" {{ in_array($class->id, old('school_classes', $assignedClassIds)) ? 'selected' : '' }}>
                                    {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}{{ $class->school ? ' (' . $class->school->name . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small>Hold Ctrl/Cmd to select multiple classes</small>
                        @error('school_classes')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="sb-btn sb-btn-primary">
                        Update Subject
                    </button>
                    <a href="{{ route('subjects.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
