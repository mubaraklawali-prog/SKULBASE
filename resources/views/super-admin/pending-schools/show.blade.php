@extends('layouts.app')

@section('title', 'Review Registration - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Review Registration</h2>
            <p class="mb-0">Review school registration application</p>
        </div>
        <a href="{{ route('pending-schools.index') }}" class="sb-btn sb-btn-secondary">
            &larr; Back to Pending Schools
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- School Details --}}
            <div class="card stat-card mb-4">
                <div class="card-header" style="background: none; border-bottom: 1px solid #e9ecef; padding: 16px 24px;">
                    <strong>School Information</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">School Name</label>
                            <div style="font-weight: 600; font-size: 16px;">{{ $school->name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">School Type</label>
                            <div>{{ $school->school_type ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Email</label>
                            <div>{{ $school->email ?? 'Not provided' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Phone</label>
                            <div>{{ $school->phone ?? 'Not provided' }}</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="sb-form-label text-muted">Address</label>
                            <div>{{ $school->address ?? 'Not provided' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Registered</label>
                            <div>{{ $school->registered_at ? $school->registered_at->format('M d, Y \a\t h:i A') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Admin Details --}}
            @if ($admin)
            <div class="card stat-card mb-4">
                <div class="card-header" style="background: none; border-bottom: 1px solid #e9ecef; padding: 16px 24px;">
                    <strong>School Administrator</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Full Name</label>
                            <div style="font-weight: 600;">{{ $admin->name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Email</label>
                            <div>{{ $admin->email }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- Actions --}}
            <div class="card stat-card mb-4">
                <div class="card-header" style="background: none; border-bottom: 1px solid #e9ecef; padding: 16px 24px;">
                    <strong>Actions</strong>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('pending-schools.approve', $school) }}">
                            @csrf
                            <button type="submit" class="sb-btn sb-btn-outline-success" style="width: 100%;"
                                    onclick="return confirm('Are you sure you want to approve this school? A {{ $plan->trial_days ?? 30 }}-day trial on {{ $plan->name ?? 'selected plan' }} will be activated.')">
                                Approve Registration
                            </button>
                        </form>

                        <button type="button" class="sb-btn sb-btn-outline-danger" style="width: 100%;"
                                data-bs-toggle="modal" data-bs-target="#rejectModal">
                            Reject Registration
                        </button>
                    </div>
                </div>
            </div>

            {{-- Status Info --}}
            <div class="card stat-card">
                <div class="card-header" style="background: none; border-bottom: 1px solid #e9ecef; padding: 16px 24px;">
                    <strong>Registration Details</strong>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="text-muted" style="font-size: 13px;">Status:</span>
                        <span class="sb-badge sb-badge-pending">Pending</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted" style="font-size: 13px;">Plan:</span>
                        <span style="font-weight: 500;">{{ $plan->name ?? 'Not selected' }}</span>
                    </div>
                    @if($plan)
                    <div class="mb-2">
                        <span class="text-muted" style="font-size: 13px;">Trial:</span>
                        <span>{{ $plan->trial_days }} days</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid #e9ecef;">
                <h5 class="modal-title" id="rejectModalLabel">Reject Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('pending-schools.reject', $school) }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Please provide a reason for rejecting this school registration:</p>
                    <div>
                        <label class="sb-form-label">Rejection Reason <span class="required">*</span></label>
                        <textarea name="rejection_reason" class="sb-form-textarea" rows="4" required
                                  placeholder="Enter the reason for rejection..."
                                  style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px; font-size: 14px; width: 100%;"></textarea>
                        @error('rejection_reason')
                            <div class="sb-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef;">
                    <button type="button" class="sb-btn sb-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="sb-btn sb-btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
