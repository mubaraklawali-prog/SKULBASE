@extends('layouts.app')

@section('title', 'Subscription Details - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Subscription #{{ $subscription->id }}</h2>
            <p class="text-muted mb-0">Subscription details for {{ $subscription->school->name ?? 'Unknown School' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('subscriptions.index') }}" class="sb-btn sb-btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Subscription Information</h5>

                    <div class="mb-3">
                        <label class="sb-form-label">School</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500; color: #333;">{{ $subscription->school->name ?? '—' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Plan</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500; color: #333;">{{ $subscription->plan->name ?? '—' }}</p>
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
                        <label class="sb-form-label">Trial</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">{{ $subscription->is_trial ? 'Yes' : 'No' }}</p>
                    </div>

                    <div class="d-flex gap-2" style="border-top: 1px solid #e9ecef; padding-top: 16px; margin-top: 8px;">
                        <span style="font-size: 12px; color: #6c757d;">Created: {{ $subscription->created_at->format('d M Y, g:i A') }}</span>
                        <span style="font-size: 12px; color: #6c757d;">|</span>
                        <span style="font-size: 12px; color: #6c757d;">Updated: {{ $subscription->updated_at->format('d M Y, g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Dates & Payment</h5>

                    <div class="mb-3">
                        <label class="sb-form-label">Starts At</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            {{ $subscription->starts_at ? $subscription->starts_at->format('d M Y, g:i A') : '—' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Expires At</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            {{ $subscription->expires_at ? $subscription->expires_at->format('d M Y, g:i A') : '—' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Trial Starts At</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            {{ $subscription->trial_starts_at ? $subscription->trial_starts_at->format('d M Y, g:i A') : '—' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Trial Ends At</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            {{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('d M Y, g:i A') : '—' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Grace Ends At</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            {{ $subscription->grace_ends_at ? $subscription->grace_ends_at->format('d M Y, g:i A') : '—' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Cancelled At</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            {{ $subscription->cancelled_at ? $subscription->cancelled_at->format('d M Y, g:i A') : '—' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Amount Paid</label>
                        <p style="margin: 0; font-size: 18px; font-weight: 600; color: #333;">{{ $subscription->formattedAmountPaid() }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="sb-form-label">Payment Reference</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">
                            @if($subscription->payment_reference)
                                <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $subscription->payment_reference }}</code>
                            @else
                                <span style="color: #6c757d;">—</span>
                            @endif
                        </p>
                    </div>

                    @if($subscription->notes)
                        <div class="mb-3">
                            <label class="sb-form-label">Notes</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $subscription->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
