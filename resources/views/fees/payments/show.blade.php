@extends('layouts.app')

@section('title', 'Payment Detail - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Payment Detail</h2>
            <p class="text-muted mb-0">Payment #{{ $payment->id }} &middot; {{ $payment->payment_date->format('M d, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('fees.payments.receipt', $payment) }}" class="sb-btn sb-btn-outline-success" target="_blank">Print Receipt</a>
            <a href="{{ route('fees.payments.index') }}" class="sb-btn sb-btn-secondary">Back to Payments</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Payment Information</h5>
                    <div class="mb-3">
                        <label class="sb-form-label">Amount Paid</label>
                        <p style="margin: 0; font-size: 22px; font-weight: 700; color: #0f5132;">₦{{ number_format($payment->amount_paid, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Payment Date</label>
                        <p style="margin: 0; font-size: 15px;">{{ $payment->payment_date->format('l, M d, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Payment Method</label>
                        <p style="margin: 0; font-size: 15px; text-transform: capitalize;">{{ $payment->payment_method }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Reference</label>
                        <p style="margin: 0; font-size: 15px;">{{ $payment->reference ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="sb-form-label">Remarks</label>
                        <p style="margin: 0; font-size: 15px;">{{ $payment->remarks ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Student & Fee Info</h5>
                    <div class="mb-3">
                        <label class="sb-form-label">Student</label>
                        <p style="margin: 0; font-size: 15px;">
                            <a href="{{ route('fees.student', $payment->student) }}" style="color: #333; text-decoration: none; font-weight: 500;">{{ $payment->student->full_name }}</a>
                            &middot; <code>{{ $payment->student->admission_number }}</code>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Fee Structure</label>
                        <p style="margin: 0; font-size: 15px;">{{ $payment->feeStructure->title ?? '—' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Class</label>
                        <p style="margin: 0; font-size: 15px;">{{ $payment->feeStructure->schoolClass->name ?? '—' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Total Fee Amount</label>
                        <p style="margin: 0; font-size: 15px;">₦{{ number_format($payment->feeStructure->amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="sb-form-label">Recorded By</label>
                        <p style="margin: 0; font-size: 15px;">{{ $payment->recorded_by ?? 'System' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
