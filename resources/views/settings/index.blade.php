@extends('layouts.app')

@section('title', 'Settings - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Settings</h2>
            <p class="text-muted mb-0">Manage your school profile and academic settings</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="border-radius: 10px;">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- School Profile --}}
    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" id="settingsForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">School Information</h5>

                        <div class="mb-3">
                            <label class="sb-form-label">School Name <span class="required">*</span></label>
                            <input type="text" name="name" class="sb-form-input @error('name') is-invalid @enderror" value="{{ old('name', $school->name) }}" required>
                            @error('name')
                                <small class="sb-form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Motto</label>
                            <input type="text" name="motto" class="sb-form-input @error('motto') is-invalid @enderror" value="{{ old('motto', $school->motto) }}" placeholder="e.g. Knowledge is Power">
                            @error('motto')
                                <small class="sb-form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="sb-form-label">Email</label>
                                <input type="email" name="email" class="sb-form-input @error('email') is-invalid @enderror" value="{{ old('email', $school->email) }}" placeholder="school@example.com">
                                @error('email')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="sb-form-label">Phone</label>
                                <input type="text" name="phone" class="sb-form-input @error('phone') is-invalid @enderror" value="{{ old('phone', $school->phone) }}" placeholder="+234 800 000 0000">
                                @error('phone')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Website</label>
                            <input type="url" name="website" class="sb-form-input @error('website') is-invalid @enderror" value="{{ old('website', $school->website) }}" placeholder="https://www.yourschool.com">
                            @error('website')
                                <small class="sb-form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Principal Name</label>
                            <input type="text" name="principal_name" class="sb-form-input @error('principal_name') is-invalid @enderror" value="{{ old('principal_name', $school->principal_name) }}" placeholder="e.g. Dr. Adebayo Johnson">
                            @error('principal_name')
                                <small class="sb-form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Address</label>
                            <input type="text" name="address" class="sb-form-input @error('address') is-invalid @enderror" value="{{ old('address', $school->address) }}" placeholder="123 School Road">
                            @error('address')
                                <small class="sb-form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="sb-form-label">City</label>
                                <input type="text" name="city" class="sb-form-input @error('city') is-invalid @enderror" value="{{ old('city', $school->city) }}" placeholder="Lagos">
                                @error('city')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="sb-form-label">State</label>
                                <input type="text" name="state" class="sb-form-input @error('state') is-invalid @enderror" value="{{ old('state', $school->state) }}" placeholder="Lagos State">
                                @error('state')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="sb-form-label">Country</label>
                                <input type="text" name="country" class="sb-form-input @error('country') is-invalid @enderror" value="{{ old('country', $school->country) }}" placeholder="Nigeria">
                                @error('country')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">School Logo</h5>

                        <div class="text-center mb-3">
                            @if($school->logo)
                                <div id="logoPreview" style="width: 120px; height: 120px; border-radius: 12px; overflow: hidden; margin: 0 auto; border: 2px solid #dee2e6;">
                                    <img src="{{ Storage::disk('public')->url($school->logo) }}" alt="School Logo" style="width: 100%; height: 100%; object-fit: cover;" id="logoImage">
                                </div>
                            @else
                                <div id="logoPreview" style="width: 120px; height: 120px; border-radius: 12px; overflow: hidden; margin: 0 auto; border: 2px dashed #dee2e6; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                    <div style="text-align: center; color: #adb5bd;">
                                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto; display: block;">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                        <span style="font-size: 11px; display: block; margin-top: 4px;">No Logo</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <input type="file" name="logo" class="sb-form-input" accept="image/jpg,image/jpeg,image/png,image/webp" onchange="previewLogo(this)">
                            <small style="color: #adb5bd;">JPG, PNG or WebP. Max 2MB.</small>
                            @error('logo')
                                <small class="sb-form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        @if($school->logo)
                            <div class="form-check mb-3">
                                <input type="checkbox" name="remove_logo" value="1" class="form-check-input" id="removeLogo">
                                <label class="form-check-label sb-form-label" for="removeLogo">Remove current logo</label>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="sb-btn sb-btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Academic Settings --}}
    <form method="POST" action="{{ route('settings.academic.update') }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Academic Settings</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="sb-form-label">Current Academic Session <span class="required">*</span></label>
                                <input type="text" name="current_session" class="sb-form-input @error('current_session') is-invalid @enderror" value="{{ old('current_session', $schoolSetting->current_session) }}" placeholder="e.g. 2026/2027" required>
                                @error('current_session')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="sb-form-label">Current Term <span class="required">*</span></label>
                                <select name="current_term" class="sb-form-select @error('current_term') is-invalid @enderror" required>
                                    <option value="">Select term...</option>
                                    <option value="First Term" {{ old('current_term', $schoolSetting->current_term) === 'First Term' ? 'selected' : '' }}>First Term</option>
                                    <option value="Second Term" {{ old('current_term', $schoolSetting->current_term) === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                    <option value="Third Term" {{ old('current_term', $schoolSetting->current_term) === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                </select>
                                @error('current_term')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="sb-form-label">School Opening Time <span class="required">*</span></label>
                                <input type="time" name="school_open_time" class="sb-form-input @error('school_open_time') is-invalid @enderror" value="{{ old('school_open_time', $schoolSetting->school_open_time ? \Carbon\Carbon::parse($schoolSetting->school_open_time)->format('H:i') : '') }}" required>
                                @error('school_open_time')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="sb-form-label">School Closing Time <span class="required">*</span></label>
                                <input type="time" name="school_close_time" class="sb-form-input @error('school_close_time') is-invalid @enderror" value="{{ old('school_close_time', $schoolSetting->school_close_time ? \Carbon\Carbon::parse($schoolSetting->school_close_time)->format('H:i') : '') }}" required>
                                @error('school_close_time')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d-grid">
                    <button type="submit" class="sb-btn sb-btn-dark">Save Academic Settings</button>
                </div>
            </div>
        </div>
    </form>

    {{-- System Settings --}}
    <form method="POST" action="{{ route('settings.system.update') }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">System Settings</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="sb-form-label">Timezone <span class="required">*</span></label>
                                <select name="timezone" class="sb-form-select @error('timezone') is-invalid @enderror" required>
                                    <option value="">Select timezone...</option>
                                    @php $selectedTz = old('timezone', $schoolSetting->timezone); @endphp
                                    @foreach(['Africa/Lagos' => 'Africa/Lagos (WAT)', 'Africa/Accra' => 'Africa/Accra (GMT)', 'Africa/Nairobi' => 'Africa/Nairobi (EAT)', 'Africa/Johannesburg' => 'Africa/Johannesburg (SAST)', 'Africa/Cairo' => 'Africa/Cairo (EET)', 'Europe/London' => 'Europe/London (GMT/BST)', 'Europe/Paris' => 'Europe/Paris (CET/CEST)', 'America/New_York' => 'America/New_York (EST/EDT)', 'America/Chicago' => 'America/Chicago (CST/CDT)', 'America/Los_Angeles' => 'America/Los_Angeles (PST/PDT)', 'Asia/Dubai' => 'Asia/Dubai (GST)', 'Asia/Kolkata' => 'Asia/Kolkata (IST)', 'Asia/Shanghai' => 'Asia/Shanghai (CST)', 'Asia/Tokyo' => 'Asia/Tokyo (JST)', 'Australia/Sydney' => 'Australia/Sydney (AEST/AEDT)', 'Pacific/Auckland' => 'Pacific/Auckland (NZST/NZDT)'] as $tz => $label)
                                        <option value="{{ $tz }}" {{ $selectedTz === $tz ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="sb-form-label">Date Format <span class="required">*</span></label>
                                <select name="date_format" class="sb-form-select @error('date_format') is-invalid @enderror" required>
                                    <option value="">Select format...</option>
                                    @php $selectedDf = old('date_format', $schoolSetting->date_format); @endphp
                                    @foreach(['d/m/Y' => 'd/m/Y (31/12/2026)', 'm/d/Y' => 'm/d/Y (12/31/2026)', 'Y-m-d' => 'Y-m-d (2026-12-31)', 'd-M-Y' => 'd-M-Y (31-Dec-2026)', 'M d, Y' => 'M d, Y (Dec 31, 2026)'] as $fmt => $label)
                                        <option value="{{ $fmt }}" {{ $selectedDf === $fmt ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('date_format')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="sb-form-label">Time Format <span class="required">*</span></label>
                                <select name="time_format" class="sb-form-select @error('time_format') is-invalid @enderror" required>
                                    <option value="">Select format...</option>
                                    @php $selectedTf = old('time_format', $schoolSetting->time_format); @endphp
                                    <option value="12 Hour" {{ $selectedTf === '12 Hour' ? 'selected' : '' }}>12 Hour (2:30 PM)</option>
                                    <option value="24 Hour" {{ $selectedTf === '24 Hour' ? 'selected' : '' }}>24 Hour (14:30)</option>
                                </select>
                                @error('time_format')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="sb-form-label">Currency <span class="required">*</span></label>
                                <select name="currency" class="sb-form-select @error('currency') is-invalid @enderror" required>
                                    <option value="">Select currency...</option>
                                    @php $selectedCur = old('currency', $schoolSetting->currency); @endphp
                                    @foreach(['NGN' => 'NGN - Nigerian Naira', 'USD' => 'USD - US Dollar', 'GBP' => 'GBP - British Pound', 'EUR' => 'EUR - Euro', 'GHS' => 'GHS - Ghanaian Cedi', 'KES' => 'KES - Kenyan Shilling', 'ZAR' => 'ZAR - South African Rand'] as $cur => $label)
                                        <option value="{{ $cur }}" {{ $selectedCur === $cur ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('currency')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="sb-form-label">Currency Symbol <span class="required">*</span></label>
                                <input type="text" name="currency_symbol" class="sb-form-input @error('currency_symbol') is-invalid @enderror" value="{{ old('currency_symbol', $schoolSetting->currency_symbol) }}" placeholder="e.g. ₦" required>
                                @error('currency_symbol')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="sb-form-label">Maintenance Mode</label>
                                <div class="form-check form-switch mt-2">
                                    <input type="hidden" name="maintenance_mode" value="0">
                                    <input type="checkbox" name="maintenance_mode" value="1" class="form-check-input" id="maintenanceMode" {{ old('maintenance_mode', $schoolSetting->maintenance_mode) ? 'checked' : '' }} style="cursor: pointer;">
                                    <label class="form-check-label sb-form-label" for="maintenanceMode" style="cursor: pointer;">{{ $schoolSetting->maintenance_mode ? 'Enabled' : 'Disabled' }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="maintenanceMessageWrapper" style="{{ old('maintenance_mode', $schoolSetting->maintenance_mode) ? '' : 'display: none;' }}">
                            <label class="sb-form-label">Maintenance Message</label>
                            <textarea name="maintenance_message" class="sb-form-textarea @error('maintenance_message') is-invalid @enderror" rows="3" placeholder="We are currently performing scheduled maintenance. Please check back later.">{{ old('maintenance_message', $schoolSetting->maintenance_message) }}</textarea>
                            @error('maintenance_message')
                                <small class="sb-form-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d-grid">
                    <button type="submit" class="sb-btn sb-btn-primary">Save System Settings</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Notification Settings --}}
    <form method="POST" action="{{ route('settings.notifications.update') }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Notification Settings</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="sb-form-label">Default Sender Name <span class="required">*</span></label>
                                <input type="text" name="default_sender_name" class="sb-form-input @error('default_sender_name') is-invalid @enderror" value="{{ old('default_sender_name', $schoolSetting->default_sender_name ?? $school->name) }}" placeholder="e.g. Skulbase Academy" required>
                                @error('default_sender_name')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="sb-form-label">Default Reply Email <span class="required">*</span></label>
                                <input type="email" name="default_reply_email" class="sb-form-input @error('default_reply_email') is-invalid @enderror" value="{{ old('default_reply_email', $schoolSetting->default_reply_email ?? $school->email) }}" placeholder="noreply@yourschool.com" required>
                                @error('default_reply_email')
                                    <small class="sb-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <h6 style="font-weight: 600; color: #1a1a2e; margin-bottom: 12px;">Notification Channels</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="email_notifications" value="0">
                                    <input type="checkbox" name="email_notifications" value="1" class="form-check-input" id="emailNotifications" {{ old('email_notifications', $schoolSetting->email_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label sb-form-label" for="emailNotifications">Email Notifications</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="assignment_notifications" value="0">
                                    <input type="checkbox" name="assignment_notifications" value="1" class="form-check-input" id="assignmentNotifications" {{ old('assignment_notifications', $schoolSetting->assignment_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label sb-form-label" for="assignmentNotifications">Assignment Notifications</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="attendance_notifications" value="0">
                                    <input type="checkbox" name="attendance_notifications" value="1" class="form-check-input" id="attendanceNotifications" {{ old('attendance_notifications', $schoolSetting->attendance_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label sb-form-label" for="attendanceNotifications">Attendance Notifications</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="result_notifications" value="0">
                                    <input type="checkbox" name="result_notifications" value="1" class="form-check-input" id="resultNotifications" {{ old('result_notifications', $schoolSetting->result_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label sb-form-label" for="resultNotifications">Result Notifications</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="fee_notifications" value="0">
                                    <input type="checkbox" name="fee_notifications" value="1" class="form-check-input" id="feeNotifications" {{ old('fee_notifications', $schoolSetting->fee_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label sb-form-label" for="feeNotifications">Fee Notifications</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="announcement_notifications" value="0">
                                    <input type="checkbox" name="announcement_notifications" value="1" class="form-check-input" id="announcementNotifications" {{ old('announcement_notifications', $schoolSetting->announcement_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label sb-form-label" for="announcementNotifications">Announcement Notifications</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="event_notifications" value="0">
                                    <input type="checkbox" name="event_notifications" value="1" class="form-check-input" id="eventNotifications" {{ old('event_notifications', $schoolSetting->event_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label sb-form-label" for="eventNotifications">Event Notifications</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="admission_notifications" value="0">
                                    <input type="checkbox" name="admission_notifications" value="1" class="form-check-input" id="admissionNotifications" {{ old('admission_notifications', $schoolSetting->admission_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label sb-form-label" for="admissionNotifications">Admission Notifications</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d-grid">
                    <button type="submit" class="sb-btn sb-btn-dark">Save Notification Settings</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('maintenanceMode').addEventListener('change', function() {
        var wrapper = document.getElementById('maintenanceMessageWrapper');
        var label = this.nextElementSibling;
        if (this.checked) {
            wrapper.style.display = '';
            label.textContent = 'Enabled';
        } else {
            wrapper.style.display = 'none';
            label.textContent = 'Disabled';
        }
    });

    function previewLogo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('logoPreview');
                preview.innerHTML = '<img src="' + e.target.result + '" alt="Logo Preview" style="width: 100%; height: 100%; object-fit: cover;" id="logoImage">';
                preview.style.border = '2px solid #dee2e6';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
