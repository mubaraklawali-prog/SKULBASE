@extends('layouts.app')

@section('title', 'Edit Parent - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Edit Parent</h2>
        <p class="text-muted mb-0">Update parent information for {{ $parent->full_name }}</p>
    </div>

    <form method="POST" action="{{ route('parents.update', $parent) }}">
        @csrf
        @method('PUT')

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Parent Information</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="school_id" class="sb-form-label">School <span class="required">*</span></label>
                        <select
                            name="school_id"
                            id="school_id"
                            class="sb-form-select @error('school_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $parent->school_id) == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="sb-form-label">First Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="first_name"
                            id="first_name"
                            value="{{ old('first_name', $parent->first_name) }}"
                            placeholder="First name"
                            class="sb-form-input @error('first_name') is-invalid @enderror"
                            required
                        >
                        @error('first_name')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="sb-form-label">Last Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="last_name"
                            id="last_name"
                            value="{{ old('last_name', $parent->last_name) }}"
                            placeholder="Last name"
                            class="sb-form-input @error('last_name') is-invalid @enderror"
                            required
                        >
                        @error('last_name')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="email" class="sb-form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $parent->email) }}"
                            placeholder="parent@example.com"
                            class="sb-form-input @error('email') is-invalid @enderror"
                        >
                        @error('email')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="phone" class="sb-form-label">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone', $parent->phone) }}"
                            placeholder="Phone number"
                            class="sb-form-input @error('phone') is-invalid @enderror"
                        >
                        @error('phone')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="address" class="sb-form-label">Address</label>
                        <input
                            type="text"
                            name="address"
                            id="address"
                            value="{{ old('address', $parent->address) }}"
                            placeholder="Home address"
                            class="sb-form-input @error('address') is-invalid @enderror"
                        >
                        @error('address')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status" class="sb-form-label">Status <span class="required">*</span></label>
                        <select
                            name="status"
                            id="status"
                            class="sb-form-select @error('status') is-invalid @enderror"
                            required
                        >
                            <option value="1" {{ old('status', $parent->status ? '1' : '0') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $parent->status ? '1' : '0') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Link Children</h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="student_ids" class="sb-form-label">Students</label>
                        <select
                            name="student_ids[]"
                            id="student_ids"
                            class="sb-form-select @error('student_ids') is-invalid @enderror"
                            multiple
                            size="5"
                        >
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ in_array($student->id, old('student_ids', $assignedStudentIds)) ? 'selected' : '' }}>
                                    {{ $student->full_name }} ({{ $student->admission_number }})
                                </option>
                            @endforeach
                        </select>
                        <small>Hold Ctrl/Cmd to select multiple students</small>
                        @error('student_ids')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-2 mb-4">
            <button type="submit" class="sb-btn sb-btn-primary">
                Update Parent
            </button>
            <a href="{{ route('parents.index') }}" class="sb-btn sb-btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
