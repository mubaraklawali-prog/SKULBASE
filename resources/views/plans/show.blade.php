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
                        <p style="margin: 0; font-size: 20px; font-weight: 600; color: #333;">
                            @if($plan->isDiscountActive() && in_array($plan->discount_scope, ['monthly', 'both']))
                                <span style="text-decoration: line-through; color: #999; font-size: 14px;">{{ $plan->formattedMonthlyPrice() }}</span>
                                <span style="color: #dc3545;">{{ $plan->formattedDiscountedMonthlyPrice() }}</span>
                            @else
                                {{ $plan->formattedMonthlyPrice() }}
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Yearly Price</label>
                        <p style="margin: 0; font-size: 20px; font-weight: 600; color: #333;">
                            @if($plan->isDiscountActive() && in_array($plan->discount_scope, ['annual', 'both']))
                                <span style="text-decoration: line-through; color: #999; font-size: 14px;">{{ $plan->formattedYearlyPrice() }}</span>
                                <span style="color: #dc3545;">{{ $plan->formattedDiscountedYearlyPrice() }}</span>
                            @else
                                {{ $plan->formattedYearlyPrice() }}
                            @endif
                        </p>
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

    {{-- Discount Details --}}
    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 24px;">
            <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Discount Settings</h5>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="sb-form-label">Discount Percentage</label>
                    <p style="margin: 0; font-size: 15px; color: #333;">{{ $plan->discount_percentage }}%</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="sb-form-label">Scope</label>
                    <p style="margin: 0; font-size: 15px; color: #333;">{{ $plan->discount_scope_label }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="sb-form-label">Start Date</label>
                    <p style="margin: 0; font-size: 15px; color: #333;">{{ $plan->discount_start_date?->format('d M Y') ?? 'Immediate' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="sb-form-label">End Date</label>
                    <p style="margin: 0; font-size: 15px; color: #333;">{{ $plan->discount_end_date?->format('d M Y') ?? 'No expiry' }}</p>
                </div>
            </div>

            <div class="mt-2">
                @if($plan->isDiscountActive())
                    <div class="alert alert-success" style="border-radius: 8px; font-size: 14px; margin-bottom: 0;">
                        <strong>Active Discount:</strong> {{ $plan->discount_percentage }}% off
                        @if(in_array($plan->discount_scope, ['monthly', 'both']))
                            — Monthly: <s>{{ $plan->formattedMonthlyPrice() }}</s> → <strong>{{ $plan->formattedDiscountedMonthlyPrice() }}</strong>
                        @endif
                        @if(in_array($plan->discount_scope, ['annual', 'both']))
                            — Yearly: <s>{{ $plan->formattedYearlyPrice() }}</s> → <strong>{{ $plan->formattedDiscountedYearlyPrice() }}</strong>
                        @endif
                    </div>
                @else
                    <div class="alert alert-secondary" style="border-radius: 8px; font-size: 14px; margin-bottom: 0;">
                        No active discount. Prices shown at regular rate.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
