@extends('layouts.app')

@section('title', 'Application ' . $admission->application_number . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $admission->application_number }}</h2>
            <p class="text-muted mb-0">Admission Application Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admissions.edit', $admission) }}" class="sb-btn sb-btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('admissions.index') }}" class="sb-btn sb-btn-secondary">
                ← Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Student Information</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div class="sb-form-label">Full Name</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->full_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div class="sb-form-label">Gender</div>
                                <div style="font-weight: 500; color: #333; text-transform: capitalize;">{{ $admission->gender }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div class="sb-form-label">Date of Birth</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->date_of_birth->format('M d, Y') }} ({{ $admission->date_of_birth->age }} years old)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div class="sb-form-label">Class Applied For</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->schoolClass->name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div class="sb-form-label">Previous School</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->previous_school ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Parent/Guardian Information</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div class="sb-form-label">Name</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->parent_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div class="sb-form-label">Phone</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->parent_phone }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div class="sb-form-label">Email</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->parent_email ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div class="sb-form-label">Address</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->address }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 16px;">Application Details</h5>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div class="sb-form-label">Application Number</div>
                        <div style="font-weight: 600; color: var(--primary);">{{ $admission->application_number }}</div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div class="sb-form-label">Status</div>
                        @if($admission->status === 'approved')
                            <span class="sb-badge sb-badge-approved">Approved</span>
                        @elseif($admission->status === 'rejected')
                            <span class="sb-badge sb-badge-rejected">Rejected</span>
                        @else
                            <span class="sb-badge sb-badge-pending">Pending</span>
                        @endif
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div class="sb-form-label">Submitted</div>
                        <div style="font-weight: 500; color: #333;">{{ $admission->created_at->format('M d, Y \a\t h:i A') }}</div>
                    </div>

                    @if($admission->passport)
                        <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                            <div class="sb-form-label">Passport</div>
                            <a href="{{ $admission->passport_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: var(--primary); font-weight: 500; text-decoration: none;">
                                View Photo
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($admission->status === 'pending')
                <div class="d-flex flex-column gap-2 mb-4">
                    <form action="{{ route('admissions.approve', $admission) }}" method="POST">
                        @csrf
                        <button type="submit" class="sb-btn sb-btn-outline-success w-100" onclick="return confirm('Are you sure you want to approve this application?');">
                            Approve Application
                        </button>
                    </form>
                    <form action="{{ route('admissions.reject', $admission) }}" method="POST">
                        @csrf
                        <button type="submit" class="sb-btn sb-btn-outline-danger w-100" onclick="return confirm('Are you sure you want to reject this application?');">
                            Reject Application
                        </button>
                    </form>
                </div>
            @endif

            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admissions.edit', $admission) }}" class="sb-btn sb-btn-secondary w-100" style="text-align: center;">
                    Edit Application
                </a>
                <form action="{{ route('admissions.destroy', $admission) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="sb-btn sb-btn-outline-danger w-100">
                        Delete Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
