@extends('layouts.app')

@section('title', $plan->name . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $plan->name }}</h2>
            <p class="text-muted mb-0">Plan details and pricing information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('plans.edit', $plan) }}" class="sb-btn sb-btn-outline-primary">
                Edit Plan
            </a>
            <a href="{{ route('plans.index') }}" class="sb-btn sb-btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Plan Information</h5>

                    <div class="mb-3">
                        <label class="sb-form-label">Name</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500; color: #333;">{{ $plan->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Slug</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $plan->slug }}</code>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Description</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">{{ $plan->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Status</label>
                        <p style="margin: 0;">
                            @if($plan->is_active)
                                <span class="sb-badge sb-badge-active">Active</span>
                            @else
                                <span class="sb-badge sb-badge-inactive">Inactive</span>
                            @endif
                        </p>
                    </div>

                    <div class="d-flex gap-2" style="border-top: 1px solid #e9ecef; padding-top: 16px; margin-top: 8px;">
                        <span style="font-size: 12px; color: #6c757d;">Created: {{ $plan->created_at->format('d M Y, g:i A') }}</span>
                        <span style="font-size: 12px; color: #6c757d;">|</span>
                        <span style="font-size: 12px; color: #6c757d;">Updated: {{ $plan->updated_at->format('d M Y, g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Pricing & Limits</h5>

                    <div class="mb-3">
                        <label class="sb-form-label">Monthly Price</label>
                        <p style="margin: 0; font-size: 20px; font-weight: 600; color: #333;">{{ $plan->formattedMonthlyPrice() }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Yearly Price</label>
                        <p style="margin: 0; font-size: 20px; font-weight: 600; color: #333;">{{ $plan->formattedYearlyPrice() }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Student Limit</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            @if($plan->is_unlimited)
                                <span class="sb-badge sb-badge-info">Unlimited</span>
                            @else
                                {{ number_format($plan->student_limit ?? 0) }} students
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Free Trial</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">{{ $plan->trial_days }} days</p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Sort Order</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">{{ $plan->sort_order }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
