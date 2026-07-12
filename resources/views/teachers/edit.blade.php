@extends('layouts.app')

@section('title', 'Edit Teacher - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Edit Teacher</h2>
        <p class="text-muted mb-0">Update {{ $teacher->full_name }} details</p>
    </div>

    <form method="POST" action="{{ route('teachers.update', $teacher) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Personal Information</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="first_name" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">First Name <span style="color: #dc3545;">*</span></label>
                        <input
                            type="text"
                            name="first_name"
                            id="first_name"
                            value="{{ old('first_name', $teacher->first_name) }}"
                            placeholder="First name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                        @error('first_name')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="last_name" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Last Name <span style="color: #dc3545;">*</span></label>
                        <input
                            type="text"
                            name="last_name"
                            id="last_name"
                            value="{{ old('last_name', $teacher->last_name) }}"
                            placeholder="Last name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                        @error('last_name')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="other_name" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Other Name</label>
                        <input
                            type="text"
                            name="other_name"
                            id="other_name"
                            value="{{ old('other_name', $teacher->other_name) }}"
                            placeholder="Other name"
                            class="form-control @error('other_name') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('other_name')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="gender" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Gender <span style="color: #dc3545;">*</span></label>
                        <select
                            name="gender"
                            id="gender"
                            class="form-control @error('gender') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $teacher->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $teacher->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="email" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $teacher->email) }}"
                            placeholder="teacher@example.com"
                            class="form-control @error('email') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('email')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="phone" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Phone <span style="color: #dc3545;">*</span></label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone', $teacher->phone) }}"
                            placeholder="Phone number"
                            class="form-control @error('phone') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
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
                        >{{ old('address', $teacher->address) }}</textarea>
                        @error('address')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Employment Details</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
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
                                <option value="{{ $school->id }}" {{ old('school_id', $teacher->school_id) == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="qualification" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Qualification</label>
                        <input
                            type="text"
                            name="qualification"
                            id="qualification"
                            value="{{ old('qualification', $teacher->qualification) }}"
                            placeholder="e.g. B.Ed, M.Sc"
                            class="form-control @error('qualification') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('qualification')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="employment_date" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Employment Date</label>
                        <input
                            type="date"
                            name="employment_date"
                            id="employment_date"
                            value="{{ old('employment_date', $teacher->employment_date?->format('Y-m-d')) }}"
                            class="form-control @error('employment_date') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('employment_date')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="photo" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Photo</label>
                        @if($teacher->photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $teacher->photo) }}" alt="Current photo" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                            </div>
                        @endif
                        <input
                            type="file"
                            name="photo"
                            id="photo"
                            accept="image/*"
                            class="form-control @error('photo') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        <small style="color: #6c757d; font-size: 12px;">JPG, PNG. Max 2MB. Leave empty to keep current.</small>
                        @error('photo')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Status <span style="color: #dc3545;">*</span></label>
                        <select
                            name="status"
                            id="status"
                            class="form-control @error('status') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                            <option value="1" {{ old('status', (int) $teacher->status) === 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', (int) $teacher->status) === 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Assignments</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subjects" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Subjects</label>
                        <select
                            name="subjects[]"
                            id="subjects"
                            class="form-control @error('subjects') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            multiple
                            size="5"
                        >
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', $assignedSubjectIds)) ? 'selected' : '' }}>
                                    {{ $subject->name }}{{ $subject->code ? ' (' . $subject->code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color: #6c757d; font-size: 12px; margin-top: 4px; display: block;">Hold Ctrl/Cmd to select multiple</small>
                        @error('subjects')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="school_classes" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Classes</label>
                        <select
                            name="school_classes[]"
                            id="school_classes"
                            class="form-control @error('school_classes') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            multiple
                            size="5"
                        >
                            @foreach($schoolClasses as $class)
                                <option value="{{ $class->id }}" {{ in_array($class->id, old('school_classes', $assignedClassIds)) ? 'selected' : '' }}>
                                    {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color: #6c757d; font-size: 12px; margin-top: 4px; display: block;">Hold Ctrl/Cmd to select multiple</small>
                        @error('school_classes')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-2 mb-4">
            <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 28px; font-weight: 500; border: none; cursor: pointer;">
                Update Teacher
            </button>
            <a href="{{ route('teachers.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 28px; font-weight: 500; text-decoration: none;">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
