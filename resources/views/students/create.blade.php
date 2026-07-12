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
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="admission_number" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Admission Number <span style="color: #dc3545;">*</span></label>
                        <input
                            type="text"
                            name="admission_number"
                            id="admission_number"
                            value="{{ old('admission_number') }}"
                            placeholder="e.g. ADM-001"
                            class="form-control @error('admission_number') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                        @error('admission_number')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="first_name" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">First Name <span style="color: #dc3545;">*</span></label>
                        <input
                            type="text"
                            name="first_name"
                            id="first_name"
                            value="{{ old('first_name') }}"
                            placeholder="First name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                        @error('first_name')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="last_name" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Last Name <span style="color: #dc3545;">*</span></label>
                        <input
                            type="text"
                            name="last_name"
                            id="last_name"
                            value="{{ old('last_name') }}"
                            placeholder="Last name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                        @error('last_name')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="gender" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Gender <span style="color: #dc3545;">*</span></label>
                        <select
                            name="gender"
                            id="gender"
                            class="form-control @error('gender') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="school_class_id" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Class</label>
                        <select
                            name="school_class_id"
                            id="school_class_id"
                            class="form-control @error('school_class_id') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                            <option value="">Select Class</option>
                            @foreach($schoolClasses as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_class_id')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Date of Birth <span style="color: #dc3545;">*</span></label>
                        <input
                            type="date"
                            name="date_of_birth"
                            id="date_of_birth"
                            value="{{ old('date_of_birth') }}"
                            class="form-control @error('date_of_birth') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                        @error('date_of_birth')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            placeholder="student@example.com"
                            class="form-control @error('email') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('email')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone') }}"
                            placeholder="Phone number"
                            class="form-control @error('phone') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('phone')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Address</label>
                        <textarea
                            name="address"
                            id="address"
                            placeholder="Home address"
                            class="form-control @error('address') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            rows="2"
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 28px; font-weight: 500; border: none; cursor: pointer;">
                        Save Student
                    </button>
                    <a href="{{ route('students.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 28px; font-weight: 500; text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
