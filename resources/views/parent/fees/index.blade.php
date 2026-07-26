@extends('layouts.app')

@section('title', "My Child's Fees - Skulbase")

@section('content')
<style>
    .child-selector .form-check {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .child-selector .form-check:hover {
        border-color: var(--primary);
        background: #f8f9ff;
    }
    .child-selector .form-check-input:checked + .form-check-label {
        font-weight: 600;
        color: #0a1628;
    }
    .child-selector .form-check:has(.form-check-input:checked) {
        border-color: var(--primary);
        background: #f0f7ff;
    }
    .fee-structure-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .fee-structure-item:last-child {
        border-bottom: none;
    }
    .fee-structure-amount {
        font-weight: 700;
        font-size: 15px;
        color: #0a1628;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-paid {
        background: #d1e7dd;
        color: #0f5132;
    }
    .status-partial {
        background: #fff3cd;
        color: #664d03;
    }
    .status-unpaid {
        background: #f8d7da;
        color: #842029;
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Child's Fees</h2>
            <p class="text-muted mb-0">View fee structures and payment history</p>
        </div>
    </div>

    @if($children->count() > 1)
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h6 style="font-weight: 600; margin-bottom: 12px; color: #0a1628;">Select Child</h6>
                <form method="GET" action="{{ route('parent.fees.index') }}" class="child-selector">
                    <div class="row g-2">
                        @foreach($children as $child)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="student_id" id="child_{{ $child->id }}" value="{{ $child->id }}" {{ old('student_id', $selectedStudentId) == $child->id ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label" for="child_{{ $child->id }}">
                                        <strong>{{ $child->full_name }}</strong>
                                        <br><small style="color: #6c757d;">{{ $child->schoolClass->name ?? '' }}{{ $child->section ? ' — ' . $child->section->name : '' }}</small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($selectedStudent)
        <div class="card stat-card mb-3">
            <div class="card-body py-3 px-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span style="background: #0a1628; color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        {{ $selectedStudent->full_name }}
                    </span>
                    <span style="background: #e7f1ff; color: #0d6efd; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        {{ $selectedStudent->schoolClass->name ?? '' }}{{ $selectedStudent->section ? ' — ' . $selectedStudent->section->name : '' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div style="width: 48px; height: 48px; background: #e7f1ff; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0d6efd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                <line x1="2" y1="10" x2="22" y2="10"></line>
                            </svg>
                        </div>
                        <h6 style="color: #6c757d; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Total Fees</h6>
                        <h4 style="color: #0a1628; font-weight: 700; margin: 0;">₦{{ number_format($summary['total_fees'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div style="width: 48px; height: 48px; background: #d1e7dd; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0f5132" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <h6 style="color: #6c757d; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Amount Paid</h6>
                        <h4 style="color: #0f5132; font-weight: 700; margin: 0;">₦{{ number_format($summary['total_paid'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div style="width: 48px; height: 48px; background: #f8d7da; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#842029" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <h6 style="color: #6c757d; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Outstanding Balance</h6>
                        <h4 style="color: #842029; font-weight: 700; margin: 0;">₦{{ number_format($summary['balance'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fee Structures --}}
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h6 style="font-weight: 600; color: #0a1628; margin-bottom: 16px;">Fee Structures</h6>
                @forelse($feeStructures as $fee)
                    <div class="fee-structure-item">
                        <div>
                            <div style="font-weight: 600; font-size: 14px; color: #0a1628;">{{ $fee->title }}</div>
                            @if($fee->description)
                                <small style="color: #6c757d;">{{ $fee->description }}</small>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="fee-structure-amount">₦{{ number_format($fee->amount, 2) }}</span>
                            @php
                                $status = $fee->computed_status ?? 'unpaid';
                                $statusClass = match($status) {
                                    'paid' => 'status-paid',
                                    'partial' => 'status-partial',
                                    default => 'status-unpaid',
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 30px 20px; color: #6c757d;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <p style="margin: 0;">No fee structures found for this student.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Payment History --}}
        <div class="card stat-card">
            <div class="card-body">
                <h6 style="font-weight: 600; color: #0a1628; margin-bottom: 16px;">Payment History</h6>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Fee</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d M Y') }}</td>
                                    <td>{{ $payment->feeStructure->name ?? '—' }}</td>
                                    <td style="font-weight: 600;">₦{{ number_format($payment->amount_paid, 2) }}</td>
                                    <td>{{ ucfirst($payment->payment_method ?? '—') }}</td>
                                    <td><code style="font-size: 12px; background: #f8f9fa; padding: 2px 8px; border-radius: 4px;">{{ $payment->reference ?? '—' }}</code></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;">
                                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                            <line x1="2" y1="10" x2="22" y2="10"></line>
                                        </svg>
                                        <p style="margin: 0;">No payment records found for this student.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px 20px; text-align: center;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
                <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">Select a Child</h5>
                <p style="color: #adb5bd; margin: 0;">Choose a child above to view their fees and payment history.</p>
            </div>
        </div>
    @endif
</div>
@endsection
