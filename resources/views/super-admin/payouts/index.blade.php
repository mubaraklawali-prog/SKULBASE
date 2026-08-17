@extends('layouts.app')

@section('title', 'Affiliate Payouts - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2>Affiliate Payouts</h2>
            <p class="mb-0">Review and process payout requests</p>
        </div>
        <a href="{{ route('affiliates.index') }}" class="sb-btn sb-btn-outline-primary sb-btn-sm">Affiliates</a>
    </div>
</div>

{{-- Overview --}}
<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">{{ number_format($totals['total']) }}</p>
            <p class="stat-label">Total Requests</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--warning);">{{ number_format($totals['pending']) }}</p>
            <p class="stat-label">Pending</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--info);">{{ number_format($totals['processing']) }}</p>
            <p class="stat-label">Processing</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--success);">{{ number_format($totals['paid']) }}</p>
            <p class="stat-label">Paid</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--danger);">{{ number_format($totals['cancelled']) }}</p>
            <p class="stat-label">Cancelled</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦{{ number_format($totals['pending_amount'], 2) }}</p>
            <p class="stat-label">Pending ₦</p>
        </div></div>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <div class="sb-search-bar mb-3">
            <form method="GET" action="{{ route('payouts.index') }}" class="d-flex gap-2 flex-wrap" style="width: 100%;">
                <select name="status" class="sb-form-select" style="width: auto;">
                    <option value="">All Statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="processing" @selected(request('status') === 'processing')>Processing</option>
                    <option value="paid" @selected(request('status') === 'paid')>Paid</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
                <button type="submit" class="sb-btn sb-btn-primary sb-btn-sm">Filter</button>
                @if (request('status'))
                    <a href="{{ route('payouts.index') }}" class="sb-btn sb-btn-secondary sb-btn-sm">Clear</a>
                @endif
            </form>
        </div>

        @if ($payouts->count() > 0)
            <div class="table-responsive">
                <table class="table sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Affiliate</th>
                            <th>Requested</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payouts as $payout)
                            <tr>
                                <td>
                                    <strong>{{ $payout->affiliate->name ?? 'Unknown' }}</strong>
                                    <br><small class="text-muted">{{ $payout->affiliate->email ?? '' }}</small>
                                </td>
                                <td>{{ $payout->requested_at ? $payout->requested_at->format('M d, Y') : '-' }}</td>
                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $payout->method)) }}
                                    @if ($payout->method === 'bank_transfer' && $payout->payout_details)
                                        <br><small class="text-muted">{{ $payout->payout_details['bank_name'] ?? '' }} · {{ $payout->payout_details['account_name'] ?? '' }} · {{ $payout->payout_details['account_number'] ?? '' }}</small>
                                    @elseif ($payout->method === 'cash')
                                        <br><small class="text-muted">Cash pickup</small>
                                    @elseif ($payout->payout_details)
                                        <br><small class="text-muted">{{ $payout->payout_details['email'] ?? $payout->payout_details['wallet'] ?? $payout->payout_details['details'] ?? '' }}</small>
                                    @endif
                                </td>
                                <td>{{ $payout->formattedAmount() }}</td>
                                <td>
                                    <span class="sb-badge {{ $payout->status_badge }}">{{ ucfirst($payout->status) }}</span>
                                </td>
                                <td class="text-end">
                                    @if ($payout->isPending())
                                        <div class="table-actions">
                                            <form method="POST" action="{{ route('payouts.approve', $payout) }}" class="d-inline"
                                                  onsubmit="return confirm('Mark this payout as approved (paid out)?');">
                                                @csrf
                                                <button type="submit" class="sb-btn sb-btn-outline-success sb-btn-sm" title="Approve">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('payouts.reject', $payout) }}" class="d-inline"
                                                  onsubmit="return confirm('Reject this payout request?');">
                                                @csrf
                                                <button type="submit" class="sb-btn sb-btn-outline-danger sb-btn-sm" title="Reject">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        @if ($payout->notes)
                                            <small class="text-muted">{{ $payout->notes }}</small>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $payouts->links() }}
            </div>
        @else
            <div class="sb-empty-state">
                <h5>No Payout Requests</h5>
                <p>Payout requests from affiliates will appear here.</p>
            </div>
        @endif
    </div>
</div>
@endsection
