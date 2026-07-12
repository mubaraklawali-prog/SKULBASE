@extends('layouts.app')

@section('title', 'Payments - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Payment Records</h2>
            <p class="text-muted mb-0">Browse all fee payments</p>
        </div>
        <a href="{{ route('fees.payments.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            + Record Payment
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('fees.payments.index') }}" class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student, reference..." style="padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px; max-width: 250px;">
                <select name="class_id" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                    @endforeach
                </select>
                <select name="method" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="transfer" {{ request('method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Card</option>
                    <option value="other" {{ request('method') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" max="{{ date('Y-m-d') }}" placeholder="From" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                <input type="date" name="date_to" value="{{ request('date_to') }}" max="{{ date('Y-m-d') }}" placeholder="To" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">Filter</button>
                @if(request()->hasAny(['search', 'class_id', 'method', 'date_from', 'date_to']))
                    <a href="{{ route('fees.payments.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Fee</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Amount</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Method</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td style="padding: 14px 20px; font-weight: 500;">
                                    <a href="{{ route('fees.student', $payment->student) }}" style="color: #333; text-decoration: none;">{{ $payment->student->full_name }}</a>
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $payment->feeStructure->title ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $payment->feeStructure->schoolClass->name ?? '—' }}</td>
                                <td style="padding: 14px 20px; font-weight: 600; color: #0f5132;">₦{{ number_format($payment->amount_paid, 2) }}</td>
                                <td style="padding: 14px 20px; text-transform: capitalize; color: #6c757d;">{{ $payment->payment_method }}</td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('fees.payments.show', $payment) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">View</a>
                                        <a href="{{ route('fees.payments.receipt', $payment) }}" class="btn btn-sm" style="background: #d1e7dd; color: #0f5132; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;" target="_blank">Receipt</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No payments found.</p>
                                </td>
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
