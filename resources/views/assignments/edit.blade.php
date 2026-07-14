@extends('layouts.app')

@section('title', 'Edit Assignment - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Assignment</h2>
            <p class="text-muted mb-0">Update assignment: {{ $assignment->title }}</p>
        </div>
        <a href="{{ route('assignments.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            ← Back to Assignments
        </a>
    </div>

    <form method="POST" action="{{ route('assignments.update', $assignment) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Assignment Details</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Title <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $assignment->title) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('title')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Description</label>
                            <textarea name="description" class="form-control" rows="3" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">{{ old('description', $assignment->description) }}</textarea>
                            @error('description')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Instructions</label>
                            <textarea name="instructions" class="form-control" rows="4" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">{{ old('instructions', $assignment->instructions) }}</textarea>
                            @error('instructions')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Attachment</label>
                            @if($assignment->attachment)
                                <div class="mb-2">
                                    <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        Current: {{ basename($assignment->attachment) }}
                                    </span>
                                    <a href="{{ $assignment->attachment_url }}" target="_blank" style="font-size: 12px; color: #4f9cf7; margin-left: 8px;">View</a>
                                </div>
                            @endif
                            <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            <small style="color: #adb5bd;">Leave empty to keep current attachment. Max 10MB.</small>
                            @error('attachment')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Settings</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Status</label>
                            <select name="status" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="draft" {{ old('status', $assignment->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $assignment->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Total Marks</label>
                            <input type="number" name="total_marks" class="form-control" value="{{ old('total_marks', $assignment->total_marks) }}" min="1" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('total_marks')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Due Date <span style="color: #dc3545;">*</span></label>
                            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $assignment->due_date->format('Y-m-d')) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('due_date')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Assignment For</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Teacher <span style="color: #dc3545;">*</span></label>
                            <select name="teacher_id" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $assignment->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Class <span style="color: #dc3545;">*</span></label>
                            <select name="class_id" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $assignment->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Subject <span style="color: #dc3545;">*</span></label>
                            <select name="subject_id" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $assignment->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer; flex: 1;">
                        Update Assignment
                    </button>
                    <a href="{{ route('assignments.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
