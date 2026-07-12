@extends('layouts.app')

@section('title', 'Monthly Collections - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Monthly Collections</h2>
            <p class="text-muted mb-0">Payment collections summary for {{ now()->parse($month)->format('F Y') }}</p>
        </div>
        <a href="{{ route('fees.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to Dashboard</a>
    </div>

    <form method="GET" action="{{ route('fees.reports.monthly-collections') }}" class="card stat-card mb-4">
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Month</label>
                    <input type="month" name="month" value="{{ $month }}" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                </div>
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">View</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">₦{{ number_format($totalCollected, 2) }}</p>
                    <p class="stat-label">Total Collected</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #1a1a2e;">{{ $paymentCount }}</p>
                    <p class="stat-label">Transactions</p>
                </div>
            </div>
        </div>
        @foreach($byMethod as $method => $data)
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center" style="padding: 20px;">
                        <p class="stat-number" style="font-size: 20px; color: #0d6efd;">₦{{ number_format($data['total'], 2) }}</p>
                        <p class="stat-label" style="text-transform: capitalize;">{{ $method }} ({{ $data['count'] }})</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($byDay->isNotEmpty())
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Daily Breakdown</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Transactions</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($byDay as $day => $data)
                                <tr>
                                    <td style="padding: 12px 16px; font-weight: 500;">{{ now()->parse($day)->format('l, M d, Y') }}</td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $data['count'] }}</td>
                                    <td style="padding: 12px 16px; font-weight: 600; color: #0f5132;">₦{{ number_format($data['total'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card stat-card">
        <div class="card-body p-0">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
                <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">All Payments ({{ now()->parse($month)->format('F Y') }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Fee</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Amount</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Method</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $payment->payment_date->format('M d') }}</td>
                                <td style="padding: 14px 20px; font-weight: 500;">
                                    <a href="{{ route('fees.student', $payment->student) }}" style="color: #333; text-decoration: none;">{{ $payment->student->full_name }}</a>
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $payment->feeStructure->title ?? '—' }}</td>
                                <td style="padding: 14px 20px; font-weight: 600; color: #0f5132;">₦{{ number_format($payment->amount_paid, 2) }}</td>
                                <td style="padding: 14px 20px; text-transform: capitalize; color: #6c757d;">{{ $payment->payment_method }}</td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <a href="{{ route('fees.payments.receipt', $payment) }}" class="btn btn-sm" style="background: #d1e7dd; color: #0f5132; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;" target="_blank">Receipt</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No payments recorded this month.</p>
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
