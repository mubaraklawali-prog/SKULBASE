@extends('layouts.app')

@section('title', 'Edit Admission - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Admission</h2>
            <p class="text-muted mb-0">Update: {{ $admission->application_number }}</p>
        </div>
        <a href="{{ route('admissions.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            ← Back to Admissions
        </a>
    </div>

    <form method="POST" action="{{ route('admissions.update', $admission) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Student Information</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Full Name <span style="color: #dc3545;">*</span></label>
                                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $admission->full_name) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('full_name')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Gender <span style="color: #dc3545;">*</span></label>
                                <select name="gender" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                    <option value="male" {{ old('gender', $admission->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $admission->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Date of Birth <span style="color: #dc3545;">*</span></label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $admission->date_of_birth->format('Y-m-d')) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('date_of_birth')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Class <span style="color: #dc3545;">*</span></label>
                                <select name="class_id" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                    <option value="">Select class...</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $admission->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Previous School</label>
                                <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school', $admission->previous_school) }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('previous_school')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Passport Photo</label>
                                @if($admission->passport)
                                    <div class="mb-2">
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            Current photo uploaded
                                        </span>
                                        <a href="{{ $admission->passport_url }}" target="_blank" style="font-size: 12px; color: #4f9cf7; margin-left: 8px;">View</a>
                                    </div>
                                @endif
                                <input type="file" name="passport" class="form-control" accept="image/*" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <small style="color: #adb5bd;">Leave empty to keep current. Max 2MB.</small>
                                @error('passport')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Parent/Guardian Information</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Parent/Guardian Name <span style="color: #dc3545;">*</span></label>
                                <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $admission->parent_name) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('parent_name')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Phone Number <span style="color: #dc3545;">*</span></label>
                                <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', $admission->parent_phone) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('parent_phone')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Email Address</label>
                                <input type="email" name="parent_email" class="form-control" value="{{ old('parent_email', $admission->parent_email) }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('parent_email')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Address <span style="color: #dc3545;">*</span></label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $admission->address) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('address')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Status</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Application Status</label>
                            <select name="status" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="pending" {{ old('status', $admission->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $admission->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status', $admission->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                            <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Application Number</div>
                            <div style="font-weight: 600; color: #4f9cf7;">{{ $admission->application_number }}</div>
                        </div>

                        <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                            <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Submitted</div>
                            <div style="font-weight: 500; color: #333;">{{ $admission->created_at->format('M d, Y \a\t h:i A') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer; flex: 1;">
                        Update Application
                    </button>
                    <a href="{{ route('admissions.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
