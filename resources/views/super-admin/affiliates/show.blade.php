@extends('layouts.app')

@section('title', 'Affiliate Details - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2>{{ $affiliate->name }}</h2>
            <p class="mb-0">{{ $affiliate->email }}{{ $affiliate->phone ? ' · '.$affiliate->phone : '' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('affiliates.index') }}" class="sb-btn sb-btn-outline-secondary sb-btn-sm">Back</a>
            @if ($affiliate->isPending() || $affiliate->isSuspended())
                <form method="POST" action="{{ route('affiliates.activate', $affiliate) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="sb-btn sb-btn-outline-success sb-btn-sm">Activate</button>
                </form>
            @endif
            @if ($affiliate->isActive())
                <form method="POST" action="{{ route('affiliates.suspend', $affiliate) }}" class="d-inline" onsubmit="return confirm('Suspend this affiliate? Their referral codes will stop working.');">
                    @csrf
                    <button type="submit" class="sb-btn sb-btn-outline-warning sb-btn-sm">Suspend</button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="card stat-card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <p class="mb-1 text-muted small">Status</p>
                @php
                    $badge = match ($affiliate->status) {
                        'active' => 'sb-badge-active',
                        'pending' => 'sb-badge-pending',
                        'suspended' => 'sb-badge-inactive',
                        default => 'sb-badge-info',
                    };
                @endphp
                <span class="sb-badge {{ $badge }}">{{ ucfirst($affiliate->status) }}</span>
            </div>
            <div class="col-md-3">
                <p class="mb-1 text-muted small">Referral Code</p>
                <span class="sb-badge sb-badge-info">{{ $affiliate->code }}</span>
            </div>
            <div class="col-md-3">
                <p class="mb-1 text-muted small">Commission Rate</p>
                <strong>{{ number_format($affiliate->effectiveCommissionRate(), 2) }}%</strong>
            </div>
            <div class="col-md-3">
                <p class="mb-1 text-muted small">Total Clicks</p>
                <strong>{{ number_format($affiliate->clicks) }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦{{ number_format($summary['total_earned'], 2) }}</p>
            <p class="stat-label">Total Earned</p>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦{{ number_format($summary['pending'], 2) }}</p>
            <p class="stat-label">Pending</p>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦{{ number_format($summary['approved'], 2) }}</p>
            <p class="stat-label">Approved</p>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦{{ number_format($summary['paid'], 2) }}</p>
            <p class="stat-label">Paid</p>
        </div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card action-card mb-4">
            <div class="card-header">Referred Schools</div>
            <div class="card-body">
                @if ($referrals->count() > 0)
                    <div class="table-responsive">
                        <table class="table sb-table mb-0">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Status</th>
                                    <th>First Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($referrals as $referral)
                                    <tr>
                                        <td>
                                            <strong>{{ $referral->school->name ?? 'Pending' }}</strong>
                                            <br><small class="text-muted">{{ $referral->referred_email }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $refBadge = match ($referral->status) {
                                                    'registered' => 'sb-badge-pending',
                                                    'approved' => 'sb-badge-info',
                                                    'converted' => 'sb-badge-active',
                                                    'expired', 'cancelled' => 'sb-badge-inactive',
                                                    default => 'sb-badge-info',
                                                };
                                            @endphp
                                            <span class="sb-badge {{ $refBadge }}">{{ ucfirst($referral->status) }}</span>
                                        </td>
                                        <td>{{ $referral->first_paid_at ? $referral->first_paid_at->format('M d, Y') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $referrals->links() }}</div>
                @else
                    <div class="sb-empty-state"><h5>No Referrals</h5><p>This affiliate has not referred any schools yet.</p></div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card action-card mb-4">
            <div class="card-header">Commissions</div>
            <div class="card-body">
                @if ($commissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table sb-table mb-0">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commissions as $commission)
                                    <tr>
                                        <td>
                                            <strong>{{ $commission->referral->school->name ?? 'Unknown' }}</strong>
                                            <br><small class="text-muted">{{ ucwords(str_replace('_', ' ', $commission->type)) }} · {{ $commission->paid_period }}</small>
                                        </td>
                                        <td>{{ $commission->formattedAmount() }}</td>
                                        <td>
                                            <span class="sb-badge {{ $commission->status_badge }}">{{ ucfirst($commission->status) }}</span>
                                        </td>
                                        <td class="text-end">
                                            @if ($commission->isPending())
                                                <div class="table-actions">
                                                    <form method="POST" action="{{ route('affiliates.commissions.approve', [$affiliate, $commission]) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="sb-btn sb-btn-outline-success sb-btn-sm" title="Approve">Approve</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('affiliates.commissions.cancel', [$affiliate, $commission]) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="sb-btn sb-btn-outline-danger sb-btn-sm" title="Cancel">Cancel</button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $commissions->links() }}</div>
                @else
                    <div class="sb-empty-state"><h5>No Commissions</h5><p>Commissions appear once referred schools start paying.</p></div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card action-card mb-4">
    <div class="card-header">Payout Requests</div>
    <div class="card-body">
        @if ($payouts->count() > 0)
            <div class="table-responsive">
                <table class="table sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Requested</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payouts as $payout)
                            <tr>
                                <td>{{ $payout->requested_at ? $payout->requested_at->format('M d, Y') : '-' }}</td>
                                <td>{{ $payout->method }}</td>
                                <td>{{ $payout->formattedAmount() }}</td>
                                <td>
                                    <span class="sb-badge {{ $payout->status_badge }}">{{ ucfirst($payout->status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $payouts->links() }}</div>
        @else
            <div class="sb-empty-state"><h5>No Payouts</h5><p>This affiliate has not requested any payouts.</p></div>
        @endif
    </div>
</div>
@endsection
