@extends('layouts.app')

@section('title', 'Create Announcement - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Create Announcement</h2>
            <p class="text-muted mb-0">Post a new announcement to the notice board</p>
        </div>
        <a href="{{ route('announcements.index') }}" class="sb-btn sb-btn-secondary">
            ← Back to Announcements
        </a>
    </div>

    <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Announcement Details</h5>

                        <div class="mb-3">
                            <label class="sb-form-label">Title <span class="required">*</span></label>
                            <input type="text" name="title" class="sb-form-input @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. School Holiday Notice" required>
                            @error('title')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Message <span class="required">*</span></label>
                            <textarea name="message" class="sb-form-textarea @error('message') is-invalid @enderror" rows="8" placeholder="Write the announcement message here..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Attachment</label>
                            <input type="file" name="attachment" class="sb-form-input @error('attachment') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                            <small>Max 10MB. Accepted: PDF, Word, Excel, PowerPoint, Images, ZIP</small>
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
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Audience <span class="required">*</span></label>
                            <select name="audience" class="sb-form-select @error('audience') is-invalid @enderror" required>
                                <option value="everyone" {{ old('audience') === 'everyone' ? 'selected' : '' }}>Everyone</option>
                                <option value="teachers" {{ old('audience') === 'teachers' ? 'selected' : '' }}>Teachers Only</option>
                                <option value="students" {{ old('audience') === 'students' ? 'selected' : '' }}>Students Only</option>
                                <option value="parents" {{ old('audience') === 'parents' ? 'selected' : '' }}>Parents Only</option>
                            </select>
                            @error('audience')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Expires At</label>
                            <input type="date" name="expires_at" class="sb-form-input @error('expires_at') is-invalid @enderror" value="{{ old('expires_at') }}" min="{{ now()->addDay()->format('Y-m-d') }}">
                            <small>Leave empty for no expiry</small>
                            @error('expires_at')
                                <div class="sb-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="sb-btn sb-btn-primary" style="flex: 1;">
                        Create Announcement
                    </button>
                    <a href="{{ route('announcements.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
