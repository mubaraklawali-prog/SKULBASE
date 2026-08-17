@extends('layouts.app')

@section('title', 'My Subscription - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>My Subscription</h2>
        <p class="text-muted mb-0">Manage your school's subscription and billing</p>
    </div>

    @if($subscription && $subscription->isTrial())
        <div class="alert alert-info d-flex align-items-center mb-4" style="border-radius: 10px; border-left: 4px solid var(--primary); background: #f0f7ff;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px; flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <div>
                <strong>Free Trial Active</strong> — You have <strong>{{ $subscription->daysRemaining() }} days</strong> remaining in your free trial. No payment required during the trial period.
            </div>
        </div>
    @endif

    @if($subscription && $subscription->isGrace())
        <div class="alert alert-warning d-flex align-items-center mb-4" style="border-radius: 10px; border-left: 4px solid #ffc107; background: #fff8e1;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffc107" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px; flex-shrink: 0;">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
            <div>
                <strong>Your trial has expired.</strong> Renew your subscription to continue using Skulbase. You have <strong>{{ $subscription->daysRemaining() }} days</strong> remaining in your grace period.
            </div>
        </div>
    @endif

    @if($subscription && $subscription->isActive())
        <div class="alert alert-success d-flex align-items-center mb-4" style="border-radius: 10px; border-left: 4px solid #28a745; background: #f0faf0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px; flex-shrink: 0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>
                <strong>Subscription Active</strong> — Your subscription is active and expires on <strong>{{ $subscription->expires_at->format('d M Y') }}</strong>.
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Current Plan</h5>

                    @if($subscription)
                        <div class="mb-3">
                            <label class="sb-form-label">Plan Name</label>
                            <p style="margin: 0; font-size: 20px; font-weight: 600; color: #333;">{{ $subscription->plan->name ?? '—' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Status</label>
                            <p style="margin: 0;">
                                <span class="sb-badge {{ $subscription->status_badge }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Billing Cycle</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ ucfirst($subscription->billing_cycle) }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">
                                {{ $subscription->isTrial() ? 'Trial Remaining' : ($subscription->isActive() ? 'Subscription Remaining' : 'Grace Remaining') }}
                            </label>
                            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #333;">
                                @if($subscription->daysRemaining() !== null)
                                    {{ $subscription->daysRemaining() }} days
                                @else
                                    —
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Student Limit</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">
                                @if($subscription->plan && $subscription->plan->is_unlimited)
                                    <span class="sb-badge sb-badge-info">Unlimited</span>
                                @else
                                    {{ number_format($subscription->plan->student_limit ?? 0) }} students
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Monthly Price</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $subscription->plan->formattedMonthlyPrice() ?? '—' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Yearly Price</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $subscription->plan->formattedYearlyPrice() ?? '—' }}</p>
                        </div>

                        <div class="d-flex gap-2 mt-3" style="border-top: 1px solid #e9ecef; padding-top: 16px;">
                            <a href="{{ route('school.subscription.checkout', ['plan_id' => $subscription->plan_id, 'billing_cycle' => $subscription->billing_cycle]) }}" class="sb-btn sb-btn-primary">
                                Renew Subscription
                            </a>
                            <a href="{{ route('school.subscription.checkout', ['plan_id' => $subscription->plan_id, 'billing_cycle' => $subscription->billing_cycle]) }}" class="sb-btn sb-btn-outline-primary">
                                Upgrade Plan
                            </a>
                        </div>
                    @else
                        <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                            <p style="margin: 0; font-size: 15px;">No active subscription found.</p>
                            <p style="margin: 8px 0 0; font-size: 14px;">Contact support to set up your subscription.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Subscription History</h5>

                    @if($history->count())
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @foreach($history as $record)
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                                    <div>
                                        <span style="font-weight: 500; font-size: 14px; color: #333;">{{ $record->plan->name ?? '—' }}</span>
                                        <div style="font-size: 12px; color: #6c757d; margin-top: 2px;">
                                            {{ ucfirst($record->billing_cycle) }} — {{ $record->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                    <span class="sb-badge {{ $record->status_badge }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                            <p style="margin: 0; font-size: 14px;">No subscription history yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
