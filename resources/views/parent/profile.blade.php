@extends('layouts.app')

@section('title', 'My Profile - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>My Profile</h2>
            <p class="text-muted mb-0">Your account information and linked children</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card">
                <div class="card-body" style="padding: 24px; text-align: center;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: #e7f1ff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 28px; color: #0d6efd; margin: 0 auto 16px;">
                        {{ substr($parent->first_name, 0, 1) }}{{ substr($parent->last_name, 0, 1) }}
                    </div>
                    <h4 style="font-weight: 600; margin-bottom: 4px;">{{ $parent->full_name }}</h4>
                    <p class="text-muted" style="font-size: 14px; margin-bottom: 16px;">{{ $parent->email }}</p>

                    <div style="text-align: left; border-top: 1px solid #e9ecef; padding-top: 16px;">
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Phone</span>
                            <span style="font-size: 13px; font-weight: 500;">{{ $parent->phone ?: 'N/A' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Address</span>
                            <span style="font-size: 13px; font-weight: 500;">{{ $parent->address ?: 'N/A' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Account Status</span>
                            <span style="font-size: 13px; font-weight: 500;">
                                @if($parent->status)
                                    <span style="color: #198754;">Active</span>
                                @else
                                    <span style="color: #dc3545;">Inactive</span>
                                @endif
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                            <span class="text-muted" style="font-size: 13px;">Login Email</span>
                            <span style="font-size: 13px; font-weight: 500;">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stat-card mt-4">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 12px;">Account Actions</h5>
                    <a href="{{ route('password.change') }}" class="sb-btn sb-btn-outline-primary sb-btn-sm w-100 mb-2">
                        Change Password
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card stat-card">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 16px;">Linked Children ({{ $children->count() }})</h5>
                    @forelse($children as $child)
                        <div style="display: flex; align-items: center; padding: 14px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 8px;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; overflow: hidden; background: #e7f1ff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; color: #0d6efd; margin-right: 12px; flex-shrink: 0;">
                                {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 500; font-size: 14px;">{{ $child->full_name }}</div>
                                <div style="color: #6c757d; font-size: 13px;">
                                    {{ $child->admission_number }}
                                    @if($child->schoolClass)
                                        &middot; {{ $child->schoolClass->name }}
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('parent.attendance.index', ['student_id' => $child->id]) }}" class="sb-btn sb-btn-outline sb-btn-sm" style="font-size: 12px;">Attendance</a>
                                <a href="{{ route('parent.results.index', ['student_id' => $child->id]) }}" class="sb-btn sb-btn-outline sb-btn-sm" style="font-size: 12px;">Results</a>
                                <a href="{{ route('parent.fees.index', ['student_id' => $child->id]) }}" class="sb-btn sb-btn-outline sb-btn-sm" style="font-size: 12px;">Fees</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0" style="font-size: 14px;">No children linked to your account.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
