@extends('layouts.app')

@section('title', 'Daily Collections - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Daily Collections</h2>
            <p class="text-muted mb-0">Payment collections for a specific day</p>
        </div>
        <a href="{{ route('fees.dashboard') }}" class="sb-btn sb-btn-secondary">Back to Dashboard</a>
    </div>

    <form method="GET" action="{{ route('fees.reports.daily-collections') }}" class="card stat-card mb-4">
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label class="sb-form-label">Date</label>
                    <input type="date" name="date" class="sb-form-input" value="{{ $date }}" max="{{ date('Y-m-d') }}" required>
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">View</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">₦{{ number_format($totalCollected, 2) }}</p>
                    <p class="stat-label">Total Collected</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #1a1a2e;">{{ $paymentCount }}</p>
                    <p class="stat-label">Transactions</p>
                </div>
            </div>
        </div>
        @foreach($byMethod as $method => $data)
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center">
                        <p class="stat-number" style="font-size: 20px; color: #0d6efd;">₦{{ number_format($data['total'], 2) }}</p>
                        <p class="stat-label" style="text-transform: capitalize;">{{ $method }} ({{ $data['count'] }})</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="sb-section-header">
                <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">Payments on {{ now()->parse($date)->format('M d, Y') }}</h5>
            </div>
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Fee</th>
                            <th>Class</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td style="font-weight: 500;">
                                    <a href="{{ route('fees.student', $payment->student) }}" style="color: #333; text-decoration: none;">{{ $payment->student->full_name }}</a>
                                </td>
                                <td>{{ $payment->feeStructure->title ?? '—' }}</td>
                                <td>{{ $payment->feeStructure->schoolClass->name ?? '—' }}</td>
                                <td style="font-weight: 600; color: #0f5132;">₦{{ number_format($payment->amount_paid, 2) }}</td>
                                <td style="text-transform: capitalize;">{{ $payment->payment_method }}</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('fees.payments.receipt', $payment) }}" class="sb-btn sb-btn-outline-success sb-btn-sm" target="_blank">Receipt</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="sb-empty-state">
                                    <p style="margin: 0; font-size: 15px;">No payments recorded on this date.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
