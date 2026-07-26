@extends('layouts.app')

@section('title', 'Payment History Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Payment History</h2>
            <p class="text-muted mb-0">All fee payment transactions</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export.payments.csv', request()->query()) }}" class="sb-btn sb-btn-primary">Export CSV</a>
            <a href="{{ route('reports.export.payments.pdf', request()->query()) }}" class="sb-btn sb-btn-danger">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="sb-btn sb-btn-secondary">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.fees.payments') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="sb-form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, adm. no...." class="sb-form-input">
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Class</label>
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Method</label>
                    <select name="method" class="sb-form-select">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ request('method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Card</option>
                        <option value="other" {{ request('method') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="sb-form-input">
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="sb-form-input">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="sb-btn sb-btn-dark w-100">Filter</button>
                </div>
            </div>
        </div>
    </form>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Fee Title</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $index => $payment)
                            <tr>
                                <td>{{ ($payments->currentPage() - 1) * $payments->perPage() + $index + 1 }}</td>
                                <td style="font-weight: 500;">{{ $payment->student->full_name ?? '—' }}</td>
                                <td>{{ $payment->feeStructure->schoolClass->name ?? '—' }}</td>
                                <td>{{ $payment->feeStructure->title ?? '—' }}</td>
                                <td style="font-weight: 600; color: #0f5132;">₦{{ number_format($payment->amount_paid, 2) }}</td>
                                <td>
                                    <span class="sb-badge sb-badge-tag">{{ $payment->payment_method }}</span>
                                </td>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="sb-empty-state">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payments->hasPages())
            <div class="card-body" style="border-top: 1px solid #f0f2f5;">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
