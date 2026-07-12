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
            <a href="{{ route('fees.payments.create', ['student_id' => $student->id]) }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Record Payment</a>
            <a href="{{ route('fees.payments.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #1a1a2e;">₦{{ number_format($totalFees, 2) }}</p>
                    <p class="stat-label">Total Fees</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">₦{{ number_format($totalPaid, 2) }}</p>
                    <p class="stat-label">Amount Paid</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: {{ $totalBalance > 0 ? '#842029' : '#0f5132' }};">₦{{ number_format($totalBalance, 2) }}</p>
                    <p class="stat-label">Balance Due</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body p-0">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
                <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">Fee Breakdown</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Fee</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Term</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Amount</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Paid</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Balance</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($financeData as $item)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $item['fee_structure']->title }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $item['fee_structure']->term ?? '—' }}</td>
                                <td style="padding: 14px 20px;">₦{{ number_format($item['fee_structure']->amount, 2) }}</td>
                                <td style="padding: 14px 20px; color: #0f5132; font-weight: 600;">₦{{ number_format($item['total_paid'], 2) }}</td>
                                <td style="padding: 14px 20px; color: {{ $item['balance'] > 0 ? '#842029' : '#0f5132' }}; font-weight: 600;">₦{{ number_format($item['balance'], 2) }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($item['balance'] <= 0)
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Paid</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Outstanding</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">No fee structures found for this class.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
                <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">Payment History</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Fee</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Amount</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Method</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $payment->feeStructure->title ?? '—' }}</td>
                                <td style="padding: 14px 20px; font-weight: 600; color: #0f5132;">₦{{ number_format($payment->amount_paid, 2) }}</td>
                                <td style="padding: 14px 20px; color: #6c757d; text-transform: capitalize;">{{ $payment->payment_method }}</td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('fees.payments.show', $payment) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">View</a>
                                        <a href="{{ route('fees.payments.receipt', $payment) }}" style="color: #0f5132; font-weight: 500; text-decoration: none; font-size: 13px;" target="_blank">Receipt</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 40px 20px; text-align: center; color: #6c757d;">No payments recorded yet.</td>
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
