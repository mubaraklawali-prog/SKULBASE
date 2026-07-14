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
            <a href="{{ route('reports.export.payments.csv', request()->query()) }}" class="btn" style="background: #198754; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Export CSV</a>
            <a href="{{ route('reports.export.payments.pdf', request()->query()) }}" class="btn" style="background: #dc3545; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.fees.payments') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, adm. no...." style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Class</label>
                    <select name="class_id" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Method</label>
                    <select name="method" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ request('method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Card</option>
                        <option value="other" {{ request('method') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer; width: 100%;">Filter</button>
                </div>
            </div>
        </div>
    </form>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Fee Title</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Amount</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Method</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $index => $payment)
                            <tr>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ ($payments->currentPage() - 1) * $payments->perPage() + $index + 1 }}</td>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $payment->student->full_name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">{{ $payment->feeStructure->schoolClass->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">{{ $payment->feeStructure->title ?? '—' }}</td>
                                <td style="padding: 14px 20px; font-weight: 600; color: #0f5132;">₦{{ number_format($payment->amount_paid, 2) }}</td>
                                <td style="padding: 14px 20px;">
                                    <span style="background: #f0f2f5; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: capitalize;">{{ $payment->payment_method }}</span>
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $payment->payment_date->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">No payments found.</td>
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
