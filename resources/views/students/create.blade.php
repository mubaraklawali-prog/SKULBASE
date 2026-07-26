@extends('layouts.app')

@section('title', 'Add Student - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Add Student</h2>
        <p class="text-muted mb-0">Register a new student</p>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('students.store') }}">
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
                        <label for="admission_number" class="sb-form-label">Admission Number <span class="required">*</span></label>
                        <input
                            type="text"
                            name="admission_number"
                            id="admission_number"
                            value="{{ old('admission_number') }}"
                            placeholder="e.g. ADM-001"
                            class="sb-form-input @error('admission_number') is-invalid @enderror"
                            required
                        >
                        @error('admission_number')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
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

                    <div class="col-md-6 mb-3">
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

                    <div class="col-md-6 mb-3">
                        <label for="gender" class="sb-form-label">Gender <span class="required">*</span></label>
                        <select
                            name="gender"
                            id="gender"
                            class="sb-form-select @error('gender') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="school_class_id" class="sb-form-label">Class</label>
                        <select
                            name="school_class_id"
                            id="school_class_id"
                            class="sb-form-select @error('school_class_id') is-invalid @enderror"
                        >
                            <option value="">Select Class</option>
                            @foreach($schoolClasses as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_class_id')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="sb-form-label">Date of Birth <span class="required">*</span></label>
                        <input
                            type="date"
                            name="date_of_birth"
                            id="date_of_birth"
                            value="{{ old('date_of_birth') }}"
                            class="sb-form-input @error('date_of_birth') is-invalid @enderror"
                            required
                        >
                        @error('date_of_birth')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="sb-form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            placeholder="student@example.com"
                            class="sb-form-input @error('email') is-invalid @enderror"
                        >
                        @error('email')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
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

                    <div class="col-md-12 mb-3">
                        <label for="address" class="sb-form-label">Address</label>
                        <textarea
                            name="address"
                            id="address"
                            placeholder="Home address"
                            class="sb-form-textarea @error('address') is-invalid @enderror"
                            rows="2"
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="border-top: 1px solid #e9ecef; padding-top: 24px; margin-top: 16px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Parent / Guardian</h5>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="existing_parent_id" class="sb-form-label">Link to Existing Parent</label>
                            <select
                                name="existing_parent_id"
                                id="existing_parent_id"
                                class="sb-form-select @error('existing_parent_id') is-invalid @enderror"
                                onchange="toggleNewParentFields()"
                            >
                                <option value="">Select an existing parent (optional)</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ old('existing_parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->full_name }} ({{ $parent->email ?? $parent->phone ?? 'No contact' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">If the parent already exists, select them here. Otherwise, fill in the new parent details below.</small>
                            @error('existing_parent_id')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="new-parent-fields" style="width: 100%;">
                            <div class="col-md-4 mb-3">
                                <label for="new_parent_first_name" class="sb-form-label">Parent First Name</label>
                                <input
                                    type="text"
                                    name="new_parent_first_name"
                                    id="new_parent_first_name"
                                    value="{{ old('new_parent_first_name') }}"
                                    placeholder="First name"
                                    class="sb-form-input @error('new_parent_first_name') is-invalid @enderror"
                                >
                                @error('new_parent_first_name')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="new_parent_last_name" class="sb-form-label">Parent Last Name</label>
                                <input
                                    type="text"
                                    name="new_parent_last_name"
                                    id="new_parent_last_name"
                                    value="{{ old('new_parent_last_name') }}"
                                    placeholder="Last name"
                                    class="sb-form-input @error('new_parent_last_name') is-invalid @enderror"
                                >
                                @error('new_parent_last_name')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="new_parent_email" class="sb-form-label">Parent Email</label>
                                <input
                                    type="email"
                                    name="new_parent_email"
                                    id="new_parent_email"
                                    value="{{ old('new_parent_email') }}"
                                    placeholder="parent@example.com"
                                    class="sb-form-input @error('new_parent_email') is-invalid @enderror"
                                >
                                @error('new_parent_email')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="new_parent_phone" class="sb-form-label">Parent Phone</label>
                                <input
                                    type="text"
                                    name="new_parent_phone"
                                    id="new_parent_phone"
                                    value="{{ old('new_parent_phone') }}"
                                    placeholder="Phone number"
                                    class="sb-form-input @error('new_parent_phone') is-invalid @enderror"
                                >
                                @error('new_parent_phone')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="new_parent_address" class="sb-form-label">Parent Address</label>
                                <input
                                    type="text"
                                    name="new_parent_address"
                                    id="new_parent_address"
                                    value="{{ old('new_parent_address') }}"
                                    placeholder="Home address"
                                    class="sb-form-input @error('new_parent_address') is-invalid @enderror"
                                >
                                @error('new_parent_address')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        name="create_parent_account"
                                        id="create_parent_account"
                                        value="1"
                                        class="form-check-input"
                                        {{ old('create_parent_account') ? 'checked' : '' }}
                                    >
                                    <label for="create_parent_account" class="form-check-label" style="font-weight: 500; margin-left: 4px;">
                                        Create login account for this parent
                                    </label>
                                </div>
                                <small class="text-muted">A login account will be created using the parent's email address. A temporary password will be generated and shown after creation.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="sb-btn sb-btn-primary">
                        Save Student
                    </button>
                    <a href="{{ route('students.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleNewParentFields() {
        const existingParent = document.getElementById('existing_parent_id').value;
        const newParentFields = document.getElementById('new-parent-fields');

        if (existingParent) {
            newParentFields.style.opacity = '0.5';
            newParentFields.style.pointerEvents = 'none';
        } else {
            newParentFields.style.opacity = '1';
            newParentFields.style.pointerEvents = 'auto';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleNewParentFields();
    });
</script>
@endsection
