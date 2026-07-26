@extends('layouts.app')

@section('title', 'Add Parent - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Add Parent</h2>
        <p class="text-muted mb-0">Register a new parent and link children</p>
    </div>

    <form method="POST" action="{{ route('parents.store') }}">
        @csrf

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
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
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
                            value="{{ old('first_name') }}"
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
                            value="{{ old('last_name') }}"
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
                            value="{{ old('email') }}"
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
                            value="{{ old('phone') }}"
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
                            value="{{ old('address') }}"
                            placeholder="Home address"
                            class="sb-form-input @error('address') is-invalid @enderror"
                        >
                        @error('address')
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
                                <option value="{{ $student->id }}" {{ in_array($student->id, old('student_ids', [])) ? 'selected' : '' }}>
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

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Login Account</h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="create_login_account"
                                id="create_login_account"
                                value="1"
                                class="form-check-input"
                                {{ old('create_login_account') ? 'checked' : '' }}
                            >
                            <label for="create_login_account" class="form-check-label" style="font-weight: 500; margin-left: 4px;">
                                Create login account for this parent
                            </label>
                        </div>
                        <small class="text-muted">A login account will be created using the parent's email address. A temporary password will be generated and shown after creation. The parent will be required to change their password on first login.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-2 mb-4">
            <button type="submit" class="sb-btn sb-btn-primary">
                Save Parent
            </button>
            <a href="{{ route('parents.index') }}" class="sb-btn sb-btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
