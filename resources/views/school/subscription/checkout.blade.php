@extends('layouts.app')

@section('title', 'Checkout - ' . $plan->name . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="mb-4">
        <h2>Checkout</h2>
        <p class="text-muted mb-0">Complete your subscription payment</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="border-radius: 10px; border-left: 4px solid #dc3545; background: #fff5f5;">
            <strong>Payment Error</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card stat-card mb-4" style="border: 2px solid var(--primary);">
                <div class="card-body" style="padding: 28px;">
                    <h5 style="font-weight: 600; margin-bottom: 24px; color: #1a1a2e;">Order Summary</h5>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">School</label>
                        <span style="font-weight: 600; color: #333;">{{ $school->name }}</span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">Plan</label>
                        <span style="font-weight: 600; color: #333; font-size: 18px;">{{ $plan->name }}</span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">Billing Cycle</label>
                        <span style="color: #333;">{{ ucfirst($billingCycle) }}</span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">Student Limit</label>
                        <span style="color: #333;">
                            @if ($plan->is_unlimited)
                                <span class="sb-badge sb-badge-info">Unlimited</span>
                            @else
                                {{ number_format($plan->student_limit ?? 0) }} students
                            @endif
                        </span>
                    </div>

                    <hr style="margin: 20px 0; border-color: #e9ecef;">

                    @if ($hasDiscount)
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <label class="sb-form-label mb-0">Original Price</label>
                            <span style="color: #6c757d; text-decoration: line-through;">₦{{ number_format($basePrice, 2) }}</span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <label class="sb-form-label mb-0">Discount ({{ $plan->discount_percentage }}%)</label>
                            <span style="color: #28a745; font-weight: 500;">-₦{{ number_format($discountAmount, 2) }}</span>
                        </div>
                    @endif

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">Email</label>
                        <span style="color: #333;">{{ $school->email }}</span>
                    </div>

                    <hr style="margin: 20px 0; border-color: #e9ecef;">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <label class="sb-form-label mb-0" style="font-size: 18px;">Total</label>
                        <span style="font-size: 24px; font-weight: 700; color: var(--primary);">₦{{ number_format($finalPrice, 2) }}</span>
                    </div>

                    <form method="POST" action="{{ route('school.subscription.pay') }}" id="payForm">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="billing_cycle" value="{{ $billingCycle }}">
                        <button type="submit" class="sb-btn sb-btn-primary w-100" style="padding: 14px; font-size: 16px; font-weight: 600;">
                            Pay ₦{{ number_format($finalPrice, 2) }} with Paystack
                        </button>
                    </form>

                    <p class="text-muted small text-center mt-3 mb-0">
                        You will be redirected to Paystack to complete your payment securely.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
