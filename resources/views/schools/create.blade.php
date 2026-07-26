@extends('layouts.app')

@section('title', 'Add School - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Add School</h2>
        <p class="text-muted mb-0">Register a new school</p>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('schools.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="sb-form-label">School Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            placeholder="Enter school name"
                            class="sb-form-input @error('name') is-invalid @enderror"
                            required
                        >
                        @error('name')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="slug" class="sb-form-label">Slug <span class="required">*</span></label>
                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            value="{{ old('slug') }}"
                            placeholder="school-slug"
                            class="sb-form-input @error('slug') is-invalid @enderror"
                            required
                        >
                        @error('slug')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="sb-form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            placeholder="school@example.com"
                            class="sb-form-input @error('email') is-invalid @enderror"
                        >
                        @error('email')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" class="sb-form-label">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone') }}"
                            placeholder="Phone number"
                            class="sb-form-input @error('phone') is-invalid @enderror"
                        >
                        @error('phone')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address" class="sb-form-label">Address</label>
                        <textarea
                            name="address"
                            id="address"
                            placeholder="Street address"
                            class="sb-form-textarea @error('address') is-invalid @enderror"
                            rows="2"
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="city" class="sb-form-label">City</label>
                        <input
                            type="text"
                            name="city"
                            id="city"
                            value="{{ old('city') }}"
                            placeholder="City"
                            class="sb-form-input @error('city') is-invalid @enderror"
                        >
                        @error('city')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="state" class="sb-form-label">State</label>
                        <input
                            type="text"
                            name="state"
                            id="state"
                            value="{{ old('state') }}"
                            placeholder="State"
                            class="sb-form-input @error('state') is-invalid @enderror"
                        >
                        @error('state')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="country" class="sb-form-label">Country</label>
                        <input
                            type="text"
                            name="country"
                            id="country"
                            value="{{ old('country', 'Nigeria') }}"
                            placeholder="Country"
                            class="sb-form-input @error('country') is-invalid @enderror"
                        >
                        @error('country')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="sb-btn sb-btn-primary">
                        Save School
                    </button>
                    <a href="{{ route('schools.index') }}" class="sb-btn sb-btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var nameInput = document.getElementById('name');
        var slugInput = document.getElementById('slug');
        var isManualSlug = false;

        slugInput.addEventListener('input', function () {
            isManualSlug = slugInput.value.trim() !== '';
        });

        nameInput.addEventListener('input', function () {
            if (!isManualSlug) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
            }
        });
    });
</script>
@endpush
@endsection
