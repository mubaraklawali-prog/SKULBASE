@extends('layouts.app')

@section('title', 'Edit School - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Edit School</h2>
        <p class="text-muted mb-0">Update {{ $school->name }} details</p>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('schools.update', $school) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">School Name <span style="color: #dc3545;">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $school->name) }}"
                            placeholder="Enter school name"
                            class="form-control @error('name') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                        @error('name')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="slug" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Slug <span style="color: #dc3545;">*</span></label>
                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            value="{{ old('slug', $school->slug) }}"
                            placeholder="school-slug"
                            class="form-control @error('slug') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            required
                        >
                        @error('slug')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $school->email) }}"
                            placeholder="school@example.com"
                            class="form-control @error('email') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('email')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone', $school->phone) }}"
                            placeholder="Phone number"
                            class="form-control @error('phone') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('phone')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Address</label>
                        <textarea
                            name="address"
                            id="address"
                            placeholder="Street address"
                            class="form-control @error('address') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                            rows="2"
                        >{{ old('address', $school->address) }}</textarea>
                        @error('address')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="city" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">City</label>
                        <input
                            type="text"
                            name="city"
                            id="city"
                            value="{{ old('city', $school->city) }}"
                            placeholder="City"
                            class="form-control @error('city') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('city')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="state" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">State</label>
                        <input
                            type="text"
                            name="state"
                            id="state"
                            value="{{ old('state', $school->state) }}"
                            placeholder="State"
                            class="form-control @error('state') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('state')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="country" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Country</label>
                        <input
                            type="text"
                            name="country"
                            id="country"
                            value="{{ old('country', $school->country) }}"
                            placeholder="Country"
                            class="form-control @error('country') is-invalid @enderror"
                            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                        >
                        @error('country')
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 28px; font-weight: 500; border: none; cursor: pointer;">
                        Update School
                    </button>
                    <a href="{{ route('schools.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 28px; font-weight: 500; text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
