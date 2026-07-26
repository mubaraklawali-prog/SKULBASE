@extends('layouts.app')

@section('title', 'Compose Message - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Compose Message</h2>
            <p class="text-muted mb-0">Send a new message</p>
        </div>
        <a href="{{ route('messages.inbox') }}" class="sb-btn sb-btn-outline-secondary">← Back to Inbox</a>
    </div>

    <form method="POST" action="{{ route('messages.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="sb-card mb-4">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Message Details</h5>

                        <div class="mb-3">
                            <label for="subject" class="sb-form-label fw-medium text-muted small">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject" class="sb-form-input" value="{{ old('subject') }}" placeholder="e.g. Parent-Teacher Meeting" required>
                            @error('subject')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="sb-form-label fw-medium text-muted small">Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" class="sb-form-input" rows="10" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="attachment" class="sb-form-label fw-medium text-muted small">Attachment</label>
                            <input type="file" name="attachment" id="attachment" class="sb-form-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                            <small class="text-muted">Max 10MB. Accepted: PDF, Word, Excel, PowerPoint, Images, ZIP</small>
                            @error('attachment')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="sb-card mb-4">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Recipient</h5>

                        <div class="mb-3">
                            <label for="recipientType" class="sb-form-label fw-medium text-muted small">Send To <span class="text-danger">*</span></label>
                            <select name="recipient_type" id="recipientType" class="sb-form-select" required>
                                <option value="direct" {{ old('recipient_type') === 'direct' || $selectedRecipient ? 'selected' : '' }}>Specific Person</option>
                                <option value="role" {{ old('recipient_type') === 'role' || $selectedRole ? 'selected' : '' }}>Role Group</option>
                            </select>
                        </div>

                        <div class="mb-3" id="directRecipient">
                            <label for="recipientSelect" class="sb-form-label fw-medium text-muted small">Select Recipient <span class="text-danger">*</span></label>
                            <select name="recipient_id" class="sb-form-select" id="recipientSelect">
                                <option value="">Choose a recipient...</option>
                                @foreach($recipients as $recipient)
                                    <option value="{{ $recipient->id }}" {{ old('recipient_id', $selectedRecipient) == $recipient->id ? 'selected' : '' }}>{{ $recipient->name }} ({{ ucfirst(str_replace('_', ' ', $recipient->role)) }})</option>
                                @endforeach
                            </select>
                            @error('recipient_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3" id="roleRecipient" style="display: none;">
                            <label for="roleSelect" class="sb-form-label fw-medium text-muted small">Select Role <span class="text-danger">*</span></label>
                            <select name="recipient_role" class="sb-form-select" id="roleSelect">
                                <option value="">Choose a role...</option>
                                <option value="teachers" {{ old('recipient_role', $selectedRole) === 'teachers' ? 'selected' : '' }}>All Teachers</option>
                                <option value="students" {{ old('recipient_role', $selectedRole) === 'students' ? 'selected' : '' }}>All Students</option>
                                <option value="parents" {{ old('recipient_role', $selectedRole) === 'parents' ? 'selected' : '' }}>All Parents</option>
                            </select>
                            @error('recipient_role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="sb-btn sb-btn-primary flex-fill">Send Message</button>
                    <a href="{{ route('messages.inbox') }}" class="sb-btn sb-btn-outline-secondary">Cancel</a>
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
