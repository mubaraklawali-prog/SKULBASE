@extends('layouts.app')

@section('title', 'Edit Admission - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Admission</h2>
            <p class="text-muted mb-0">Update: {{ $admission->application_number }}</p>
        </div>
        <a href="{{ route('admissions.index') }}" class="sb-btn sb-btn-secondary">
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
                                <label class="sb-form-label">Full Name <span class="required">*</span></label>
                                <input type="text" name="full_name" class="sb-form-input @error('full_name') is-invalid @enderror" value="{{ old('full_name', $admission->full_name) }}" required>
                                @error('full_name')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Gender <span class="required">*</span></label>
                                <select name="gender" class="sb-form-select @error('gender') is-invalid @enderror" required>
                                    <option value="male" {{ old('gender', $admission->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $admission->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Date of Birth <span class="required">*</span></label>
                                <input type="date" name="date_of_birth" class="sb-form-input @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $admission->date_of_birth->format('Y-m-d')) }}" required>
                                @error('date_of_birth')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Class <span class="required">*</span></label>
                                <select name="class_id" class="sb-form-select @error('class_id') is-invalid @enderror" required>
                                    <option value="">Select class...</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $admission->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Previous School</label>
                                <input type="text" name="previous_school" class="sb-form-input @error('previous_school') is-invalid @enderror" value="{{ old('previous_school', $admission->previous_school) }}">
                                @error('previous_school')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Passport Photo</label>
                                @if($admission->passport)
                                    <div class="mb-2">
                                        <span class="sb-badge sb-badge-info">
                                            Current photo uploaded
                                        </span>
                                        <a href="{{ $admission->passport_url }}" target="_blank" style="font-size: 12px; color: var(--primary); margin-left: 8px;">View</a>
                                    </div>
                                @endif
                                <input type="file" name="passport" class="sb-form-input @error('passport') is-invalid @enderror" accept="image/*">
                                <small>Leave empty to keep current. Max 2MB.</small>
                                @error('passport')
                                    <div class="sb-form-error">{{ $message }}</div>
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
                                <label class="sb-form-label">Parent/Guardian Name <span class="required">*</span></label>
                                <input type="text" name="parent_name" class="sb-form-input @error('parent_name') is-invalid @enderror" value="{{ old('parent_name', $admission->parent_name) }}" required>
                                @error('parent_name')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Phone Number <span class="required">*</span></label>
                                <input type="text" name="parent_phone" class="sb-form-input @error('parent_phone') is-invalid @enderror" value="{{ old('parent_phone', $admission->parent_phone) }}" required>
                                @error('parent_phone')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Email Address</label>
                                <input type="email" name="parent_email" class="sb-form-input @error('parent_email') is-invalid @enderror" value="{{ old('parent_email', $admission->parent_email) }}">
                                @error('parent_email')
                                    <div class="sb-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="sb-form-label">Address <span class="required">*</span></label>
                                <input type="text" name="address" class="sb-form-input @error('address') is-invalid @enderror" value="{{ old('address', $admission->address) }}" required>
                                @error('address')
                                    <div class="sb-form-error">{{ $message }}</div>
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
                            <label class="sb-form-label">Application Status</label>
                            <select name="status" class="sb-form-select @error('status') is-invalid @enderror">
                                <option value="pending" {{ old('status', $admission->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $admission->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status', $admission->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                            <div class="sb-form-label">Application Number</div>
                            <div style="font-weight: 600; color: var(--primary);">{{ $admission->application_number }}</div>
                        </div>

                        <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                            <div class="sb-form-label">Submitted</div>
                            <div style="font-weight: 500; color: #333;">{{ $admission->created_at->format('M d, Y \a\t h:i A') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="sb-btn sb-btn-primary" style="flex: 1;">
                        Update Application
                    </button>
                    <a href="{{ route('admissions.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
