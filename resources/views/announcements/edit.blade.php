@extends('layouts.app')

@section('title', 'Edit Announcement - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Announcement</h2>
            <p class="text-muted mb-0">Update: {{ $announcement->title }}</p>
        </div>
        <a href="{{ route('announcements.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            ← Back to Announcements
        </a>
    </div>

    <form method="POST" action="{{ route('announcements.update', $announcement) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Announcement Details</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Title <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('title')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Message <span style="color: #dc3545;">*</span></label>
                            <textarea name="message" class="form-control" rows="8" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">{{ old('message', $announcement->message) }}</textarea>
                            @error('message')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Attachment</label>
                            @if($announcement->attachment)
                                <div class="mb-2">
                                    <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        Current: {{ basename($announcement->attachment) }}
                                    </span>
                                    <a href="{{ $announcement->attachment_url }}" target="_blank" style="font-size: 12px; color: #4f9cf7; margin-left: 8px;">View</a>
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
                                <option value="draft" {{ old('status', $announcement->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $announcement->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Audience <span style="color: #dc3545;">*</span></label>
                            <select name="audience" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="everyone" {{ old('audience', $announcement->audience) === 'everyone' ? 'selected' : '' }}>Everyone</option>
                                <option value="teachers" {{ old('audience', $announcement->audience) === 'teachers' ? 'selected' : '' }}>Teachers Only</option>
                                <option value="students" {{ old('audience', $announcement->audience) === 'students' ? 'selected' : '' }}>Students Only</option>
                                <option value="parents" {{ old('audience', $announcement->audience) === 'parents' ? 'selected' : '' }}>Parents Only</option>
                            </select>
                            @error('audience')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Expires At</label>
                            <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $announcement->expires_at?->format('Y-m-d')) }}" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            <small style="color: #adb5bd;">Leave empty for no expiry</small>
                            @error('expires_at')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer; flex: 1;">
                        Update Announcement
                    </button>
                    <a href="{{ route('announcements.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
