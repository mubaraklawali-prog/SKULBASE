@extends('layouts.app')

@section('title', $student->full_name . ' Finance - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $student->full_name }}</h2>
            <p class="text-muted mb-0">Finance Profile &middot; {{ $student->schoolClass->name ?? 'No Class' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('fees.payments.create', ['student_id' => $student->id]) }}" class="sb-btn sb-btn-primary">Record Payment</a>
            <a href="{{ route('fees.payments.index') }}" class="sb-btn sb-btn-secondary">Back</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #1a1a2e;">₦{{ number_format($totalFees, 2) }}</p>
                    <p class="stat-label">Total Fees</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">₦{{ number_format($totalPaid, 2) }}</p>
                    <p class="stat-label">Amount Paid</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: {{ $totalBalance > 0 ? '#842029' : '#0f5132' }};">₦{{ number_format($totalBalance, 2) }}</p>
                    <p class="stat-label">Balance Due</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body p-0">
            <div class="sb-section-header">
                <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">Fee Breakdown</h5>
            </div>
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Fee</th>
                            <th>Term</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($financeData as $item)
                            <tr>
                                <td style="font-weight: 500;">{{ $item['fee_structure']->title }}</td>
                                <td>{{ $item['fee_structure']->term ?? '—' }}</td>
                                <td>₦{{ number_format($item['fee_structure']->amount, 2) }}</td>
                                <td style="color: #0f5132; font-weight: 600;">₦{{ number_format($item['total_paid'], 2) }}</td>
                                <td style="color: {{ $item['balance'] > 0 ? '#842029' : '#0f5132' }}; font-weight: 600;">₦{{ number_format($item['balance'], 2) }}</td>
                                <td>
                                    @if($item['balance'] <= 0)
                                        <span class="sb-badge sb-badge-paid">Paid</span>
                                    @else
                                        <span class="sb-badge sb-badge-unpaid">Outstanding</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="sb-empty-state">No fee structures found for this class.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="sb-section-header">
                <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">Payment History</h5>
            </div>
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Fee</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="fw-medium">{{ $payment->feeStructure->title ?? '—' }}</td>
                                <td class="fw-semibold" style="color: #0f5132;">₦{{ number_format($payment->amount_paid, 2) }}</td>
                                <td class="text-capitalize">{{ $payment->payment_method }}</td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <a href="{{ route('fees.payments.show', $payment) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                        <a href="{{ route('fees.payments.receipt', $payment) }}" class="sb-btn sb-btn-sm sb-btn-outline-success" target="_blank">Receipt</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="sb-empty-state">No payments recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($payments->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
