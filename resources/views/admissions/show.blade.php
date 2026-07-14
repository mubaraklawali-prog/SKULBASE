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
            <a href="{{ route('admissions.edit', $admission) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('admissions.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                ← Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background: #d1e7dd; border-color: #badbcc; color: #0f5132;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px;">Student Information</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Full Name</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->full_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Gender</div>
                                <div style="font-weight: 500; color: #333; text-transform: capitalize;">{{ $admission->gender }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Date of Birth</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->date_of_birth->format('M d, Y') }} ({{ $admission->date_of_birth->age }} years old)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Class Applied For</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->schoolClass->name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Previous School</div>
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
                                <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Name</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->parent_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Phone</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->parent_phone }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Email</div>
                                <div style="font-weight: 500; color: #333;">{{ $admission->parent_email ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px;">
                                <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Address</div>
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
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Application Number</div>
                        <div style="font-weight: 600; color: #4f9cf7;">{{ $admission->application_number }}</div>
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Status</div>
                        @if($admission->status === 'approved')
                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Approved</span>
                        @elseif($admission->status === 'rejected')
                            <span style="background: #f8d7da; color: #dc3545; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Rejected</span>
                        @else
                            <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Pending</span>
                        @endif
                    </div>

                    <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Submitted</div>
                        <div style="font-weight: 500; color: #333;">{{ $admission->created_at->format('M d, Y \a\t h:i A') }}</div>
                    </div>

                    @if($admission->passport)
                        <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                            <div style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Passport</div>
                            <a href="{{ $admission->passport_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: #4f9cf7; font-weight: 500; text-decoration: none;">
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
                        <button type="submit" class="btn w-100" style="background: #d1e7dd; color: #0f5132; border-radius: 8px; padding: 10px 20px; font-weight: 600; border: none; cursor: pointer;" onclick="return confirm('Are you sure you want to approve this application?');">
                            Approve Application
                        </button>
                    </form>
                    <form action="{{ route('admissions.reject', $admission) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn w-100" style="background: #f8d7da; color: #dc3545; border-radius: 8px; padding: 10px 20px; font-weight: 600; border: none; cursor: pointer;" onclick="return confirm('Are you sure you want to reject this application?');">
                            Reject Application
                        </button>
                    </form>
                </div>
            @endif

            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admissions.edit', $admission) }}" class="btn w-100" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none; text-align: center;">
                    Edit Application
                </a>
                <form action="{{ route('admissions.destroy', $admission) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn w-100" style="background: #f8d7da; color: #dc3545; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer;">
                        Delete Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
