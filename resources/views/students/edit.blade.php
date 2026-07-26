@extends('layouts.app')

@section('title', 'Edit Student - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Edit Student</h2>
        <p class="text-muted mb-0">Update {{ $student->full_name }} details</p>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('students.update', $student) }}">
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
                                <option value="{{ $school->id }}" {{ old('school_id', $student->school_id) == $school->id ? 'selected' : '' }}>
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
                            value="{{ old('admission_number', $student->admission_number) }}"
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
                            value="{{ old('first_name', $student->first_name) }}"
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
                            value="{{ old('last_name', $student->last_name) }}"
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
                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
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
                                <option value="{{ $class->id }}" {{ old('school_class_id', $student->school_class_id) == $class->id ? 'selected' : '' }}>
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
                            value="{{ old('date_of_birth', $student->date_of_birth->format('Y-m-d')) }}"
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
                            value="{{ old('email', $student->email) }}"
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
                            value="{{ old('phone', $student->phone) }}"
                            placeholder="Phone number"
                            class="sb-form-input @error('phone') is-invalid @enderror"
                        >
                        @error('phone')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="address" class="sb-form-label">Address</label>
                        <textarea
                            name="address"
                            id="address"
                            placeholder="Home address"
                            class="sb-form-textarea @error('address') is-invalid @enderror"
                            rows="2"
                        >{{ old('address', $student->address) }}</textarea>
                        @error('address')
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
                            <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="sb-btn sb-btn-primary">
                        Update Student
                    </button>
                    <a href="{{ route('students.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
