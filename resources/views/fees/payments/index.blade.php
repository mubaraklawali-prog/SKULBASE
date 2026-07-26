@extends('layouts.app')

@section('title', 'Payments - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Payment Records</h2>
            <p class="text-muted mb-0">Browse all fee payments</p>
        </div>
        <a href="{{ route('fees.payments.create') }}" class="sb-btn sb-btn-primary">
            + Record Payment
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('fees.payments.index') }}" class="sb-search-bar align-items-end">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student, reference..." class="sb-form-input">
                <select name="class_id" class="sb-form-select">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                    @endforeach
                </select>
                <select name="method" class="sb-form-select">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="transfer" {{ request('method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Card</option>
                    <option value="other" {{ request('method') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" max="{{ date('Y-m-d') }}" placeholder="From" class="sb-form-input">
                <input type="date" name="date_to" value="{{ request('date_to') }}" max="{{ date('Y-m-d') }}" placeholder="To" class="sb-form-input">
                <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
                @if(request()->hasAny(['search', 'class_id', 'method', 'date_from', 'date_to']))
                    <a href="{{ route('fees.payments.index') }}" class="sb-btn sb-btn-secondary">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Fee</th>
                            <th>Class</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="text-muted">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('fees.student', $payment->student) }}" style="color: #333; text-decoration: none; font-weight: 500;">{{ $payment->student->full_name }}</a>
                                </td>
                                <td class="text-muted">{{ $payment->feeStructure->title ?? '—' }}</td>
                                <td class="text-muted">{{ $payment->feeStructure->schoolClass->name ?? '—' }}</td>
                                <td style="font-weight: 600; color: #0f5132;">₦{{ number_format($payment->amount_paid, 2) }}</td>
                                <td style="text-transform: capitalize;" class="text-muted">{{ $payment->payment_method }}</td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <a href="{{ route('fees.payments.show', $payment) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                        <a href="{{ route('fees.payments.receipt', $payment) }}" class="sb-btn sb-btn-sm sb-btn-outline-success" target="_blank">Receipt</a>
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
        <div class="mt-3 d-flex justify-content-center">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
