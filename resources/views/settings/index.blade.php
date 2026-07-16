@extends('layouts.app')

@section('title', 'School Profile - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Settings</h2>
            <p class="text-muted mb-0">Manage your school profile</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background: #d1e7dd; border-color: #badbcc; color: #0f5132;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" id="settingsForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">School Information</h5>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">School Name <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $school->name) }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('name')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Motto</label>
                            <input type="text" name="motto" class="form-control" value="{{ old('motto', $school->motto) }}" placeholder="e.g. Knowledge is Power" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('motto')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $school->email) }}" placeholder="school@example.com" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('email')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $school->phone) }}" placeholder="+234 800 000 0000" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('phone')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Website</label>
                            <input type="url" name="website" class="form-control" value="{{ old('website', $school->website) }}" placeholder="https://www.yourschool.com" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('website')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $school->address) }}" placeholder="123 School Road" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            @error('address')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $school->city) }}" placeholder="Lagos" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('city')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">State</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state', $school->state) }}" placeholder="Lagos State" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('state')
                                    <small style="color: #dc3545;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country', $school->country) }}" placeholder="Nigeria" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                                @error('country')
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
                        <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">School Logo</h5>

                        <div class="text-center mb-3">
                            @if($school->logo)
                                <div id="logoPreview" style="width: 120px; height: 120px; border-radius: 12px; overflow: hidden; margin: 0 auto; border: 2px solid #dee2e6;">
                                    <img src="{{ Storage::disk('public')->url($school->logo) }}" alt="School Logo" style="width: 100%; height: 100%; object-fit: cover;" id="logoImage">
                                </div>
                            @else
                                <div id="logoPreview" style="width: 120px; height: 120px; border-radius: 12px; overflow: hidden; margin: 0 auto; border: 2px dashed #dee2e6; display: flex; align-items: center; justify-content: center; background: #f8f9fa;" id="logoPlaceholder">
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
                            <input type="file" name="logo" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp" onchange="previewLogo(this)" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                            <small style="color: #adb5bd;">JPG, PNG or WebP. Max 2MB.</small>
                            @error('logo')
                                <small style="color: #dc3545;">{{ $message }}</small>
                            @enderror
                        </div>

                        @if($school->logo)
                            <div class="form-check mb-3">
                                <input type="checkbox" name="remove_logo" value="1" class="form-check-input" id="removeLogo">
                                <label class="form-check-label" for="removeLogo" style="font-size: 13px; color: #6c757d;">Remove current logo</label>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 12px 24px; font-weight: 500; border: none; cursor: pointer;">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
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
