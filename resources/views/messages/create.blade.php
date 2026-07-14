@extends('layouts.app')

@section('title', 'Compose Message - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Compose Message</h2>
            <p class="text-muted mb-0">Send a new message</p>
        </div>
        <a href="{{ route('messages.inbox') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            ← Back to Inbox
        </a>
    </div>

    <form method="POST" action="{{ route('messages.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Message Details</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Subject <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="e.g. Parent-Teacher Meeting" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('subject')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Message <span style="color: #dc3545;">*</span></label>
                            <textarea name="message" class="form-control" rows="10" placeholder="Write your message here..." required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">{{ old('message') }}</textarea>
                            @error('message')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Attachment</label>
                            <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            <small style="color: #adb5bd;">Max 10MB. Accepted: PDF, Word, Excel, PowerPoint, Images, ZIP</small>
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
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Recipient</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Send To <span style="color: #dc3545;">*</span></label>
                            <select name="recipient_type" id="recipientType" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="direct" {{ old('recipient_type') === 'direct' || $selectedRecipient ? 'selected' : '' }}>Specific Person</option>
                                <option value="role" {{ old('recipient_type') === 'role' || $selectedRole ? 'selected' : '' }}>Role Group</option>
                            </select>
                        </div>

                        <div class="mb-3" id="directRecipient">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Select Recipient <span style="color: #dc3545;">*</span></label>
                            <select name="recipient_id" class="form-control" id="recipientSelect" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="">Choose a recipient...</option>
                                @foreach($recipients as $recipient)
                                    <option value="{{ $recipient->id }}" {{ old('recipient_id', $selectedRecipient) == $recipient->id ? 'selected' : '' }}>{{ $recipient->name }} ({{ ucfirst(str_replace('_', ' ', $recipient->role)) }})</option>
                                @endforeach
                            </select>
                            @error('recipient_id')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3" id="roleRecipient" style="display: none;">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Select Role <span style="color: #dc3545;">*</span></label>
                            <select name="recipient_role" class="form-control" id="roleSelect" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                <option value="">Choose a role...</option>
                                <option value="teachers" {{ old('recipient_role', $selectedRole) === 'teachers' ? 'selected' : '' }}>All Teachers</option>
                                <option value="students" {{ old('recipient_role', $selectedRole) === 'students' ? 'selected' : '' }}>All Students</option>
                                <option value="parents" {{ old('recipient_role', $selectedRole) === 'parents' ? 'selected' : '' }}>All Parents</option>
                            </select>
                            @error('recipient_role')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer; flex: 1;">
                        Send Message
                    </button>
                    <a href="{{ route('messages.inbox') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('recipientType');
        const directDiv = document.getElementById('directRecipient');
        const roleDiv = document.getElementById('roleRecipient');

        function toggleRecipientType() {
            if (typeSelect.value === 'direct') {
                directDiv.style.display = 'block';
                roleDiv.style.display = 'none';
                document.getElementById('recipientSelect').required = true;
                document.getElementById('roleSelect').required = false;
                document.getElementById('roleSelect').value = '';
            } else {
                directDiv.style.display = 'none';
                roleDiv.style.display = 'block';
                document.getElementById('recipientSelect').required = false;
                document.getElementById('recipientSelect').value = '';
                document.getElementById('roleSelect').required = true;
            }
        }

        typeSelect.addEventListener('change', toggleRecipientType);
        toggleRecipientType();
    });
</script>
@endpush
@endsection
