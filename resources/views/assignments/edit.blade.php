@extends('layouts.app')

@section('title', 'Edit Assignment - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Assignment</h2>
            <p class="text-muted mb-0">Update assignment: {{ $assignment->title }}</p>
        </div>
        <a href="{{ route('assignments.index') }}" class="sb-btn sb-btn-secondary">
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
                            <label class="sb-form-label">Title <span class="required">*</span></label>
                            <input type="text" name="title" class="sb-form-input @error('title') is-invalid @enderror" value="{{ old('title', $assignment->title) }}" required>
                            @error('title')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Description</label>
                            <textarea name="description" class="sb-form-textarea @error('description') is-invalid @enderror" rows="3">{{ old('description', $assignment->description) }}</textarea>
                            @error('description')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Instructions</label>
                            <textarea name="instructions" class="sb-form-textarea @error('instructions') is-invalid @enderror" rows="4">{{ old('instructions', $assignment->instructions) }}</textarea>
                            @error('instructions')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Attachment</label>
                            @if($assignment->attachment)
                                <div class="mb-2">
                                    <span class="sb-badge sb-badge-info">
                                        Current: {{ basename($assignment->attachment) }}
                                    </span>
                                    <a href="{{ $assignment->attachment_url }}" target="_blank" style="font-size: 12px; color: var(--primary); margin-left: 8px;">View</a>
                                </div>
                            @endif
                            <input type="file" name="attachment" class="sb-form-input @error('attachment') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                            <small>Leave empty to keep current attachment. Max 10MB.</small>
                            @error('attachment')
                                <div class="sb-form-error">{{ $message }}</div>
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
                            <label class="sb-form-label">Status</label>
                            <select name="status" class="sb-form-select @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status', $assignment->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $assignment->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Total Marks</label>
                            <input type="number" name="total_marks" class="sb-form-input @error('total_marks') is-invalid @enderror" value="{{ old('total_marks', $assignment->total_marks) }}" min="1">
                            @error('total_marks')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Due Date <span class="required">*</span></label>
                            <input type="date" name="due_date" class="sb-form-input @error('due_date') is-invalid @enderror" value="{{ old('due_date', $assignment->due_date->format('Y-m-d')) }}" required>
                            @error('due_date')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Assignment For</h5>

                        <div class="mb-3">
                            <label class="sb-form-label">Teacher <span class="required">*</span></label>
                            <select name="teacher_id" class="sb-form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $assignment->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Class <span class="required">*</span></label>
                            <select name="class_id" class="sb-form-select @error('class_id') is-invalid @enderror" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $assignment->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Subject <span class="required">*</span></label>
                            <select name="subject_id" class="sb-form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $assignment->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="sb-btn sb-btn-primary" style="flex: 1;">
                        Update Assignment
                    </button>
                    <a href="{{ route('assignments.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
