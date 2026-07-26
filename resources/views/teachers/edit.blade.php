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
                        <label for="first_name" class="sb-form-label">First Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="first_name"
                            id="first_name"
                            value="{{ old('first_name', $teacher->first_name) }}"
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
                            value="{{ old('last_name', $teacher->last_name) }}"
                            placeholder="Last name"
                            class="sb-form-input @error('last_name') is-invalid @enderror"
                            required
                        >
                        @error('last_name')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="other_name" class="sb-form-label">Other Name</label>
                        <input
                            type="text"
                            name="other_name"
                            id="other_name"
                            value="{{ old('other_name', $teacher->other_name) }}"
                            placeholder="Other name"
                            class="sb-form-input @error('other_name') is-invalid @enderror"
                        >
                        @error('other_name')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="gender" class="sb-form-label">Gender <span class="required">*</span></label>
                        <select
                            name="gender"
                            id="gender"
                            class="sb-form-select @error('gender') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $teacher->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $teacher->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="email" class="sb-form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $teacher->email) }}"
                            placeholder="teacher@example.com"
                            class="sb-form-input @error('email') is-invalid @enderror"
                        >
                        @error('email')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="phone" class="sb-form-label">Phone <span class="required">*</span></label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone', $teacher->phone) }}"
                            placeholder="Phone number"
                            class="sb-form-input @error('phone') is-invalid @enderror"
                            required
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
                        >{{ old('address', $teacher->address) }}</textarea>
                        @error('address')
                            <div class="sb-form-error">{{ $message }}</div>
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
                        <label for="school_id" class="sb-form-label">School <span class="required">*</span></label>
                        <select
                            name="school_id"
                            id="school_id"
                            class="sb-form-select @error('school_id') is-invalid @enderror"
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
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="qualification" class="sb-form-label">Qualification</label>
                        <input
                            type="text"
                            name="qualification"
                            id="qualification"
                            value="{{ old('qualification', $teacher->qualification) }}"
                            placeholder="e.g. B.Ed, M.Sc"
                            class="sb-form-input @error('qualification') is-invalid @enderror"
                        >
                        @error('qualification')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="employment_date" class="sb-form-label">Employment Date</label>
                        <input
                            type="date"
                            name="employment_date"
                            id="employment_date"
                            value="{{ old('employment_date', $teacher->employment_date?->format('Y-m-d')) }}"
                            class="sb-form-input @error('employment_date') is-invalid @enderror"
                        >
                        @error('employment_date')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="photo" class="sb-form-label">Photo</label>
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
                            class="sb-form-input @error('photo') is-invalid @enderror"
                        >
                        <small>JPG, PNG. Max 2MB. Leave empty to keep current.</small>
                        @error('photo')
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
                            <option value="1" {{ old('status', (int) $teacher->status) === 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', (int) $teacher->status) === 0 ? 'selected' : '' }}>Inactive</option>
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
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Assignments</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subjects" class="sb-form-label">Subjects</label>
                        <select
                            name="subjects[]"
                            id="subjects"
                            class="sb-form-select @error('subjects') is-invalid @enderror"
                            multiple
                            size="5"
                        >
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', $assignedSubjectIds)) ? 'selected' : '' }}>
                                    {{ $subject->name }}{{ $subject->code ? ' (' . $subject->code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small>Hold Ctrl/Cmd to select multiple</small>
                        @error('subjects')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="school_classes" class="sb-form-label">Classes</label>
                        <select
                            name="school_classes[]"
                            id="school_classes"
                            class="sb-form-select @error('school_classes') is-invalid @enderror"
                            multiple
                            size="5"
                        >
                            @foreach($schoolClasses as $class)
                                <option value="{{ $class->id }}" {{ in_array($class->id, old('school_classes', $assignedClassIds)) ? 'selected' : '' }}>
                                    {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small>Hold Ctrl/Cmd to select multiple</small>
                        @error('school_classes')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Teacher Permissions</h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="can_mark_attendance"
                                id="can_mark_attendance"
                                value="1"
                                class="form-check-input"
                                {{ old('can_mark_attendance', $teacher->can_mark_attendance) ? 'checked' : '' }}
                            >
                            <label for="can_mark_attendance" class="form-check-label" style="font-weight: 500; margin-left: 4px;">
                                Can Mark Attendance
                            </label>
                        </div>
                        <small class="text-muted">Allow this teacher to view and record student attendance.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-2 mb-4">
            <button type="submit" class="sb-btn sb-btn-primary">
                Update Teacher
            </button>
            <a href="{{ route('teachers.index') }}" class="sb-btn sb-btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
