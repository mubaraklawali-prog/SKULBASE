@extends('layouts.app')

@section('title', 'Affiliates - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2>Affiliates</h2>
            <p class="mb-0">Manage referral partners and their commissions</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('affiliates.settings') }}" class="sb-btn sb-btn-outline-primary sb-btn-sm">Program Settings</a>
            <a href="{{ route('payouts.index') }}" class="sb-btn sb-btn-outline-primary sb-btn-sm">Payouts</a>
        </div>
    </div>
</div>

{{-- Overview --}}
<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">{{ number_format($totals['total']) }}</p>
            <p class="stat-label">Total</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--success);">{{ number_format($totals['active']) }}</p>
            <p class="stat-label">Active</p>
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
            <p class="stat-number" style="color: var(--danger);">{{ number_format($totals['suspended']) }}</p>
            <p class="stat-label">Suspended</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">{{ number_format($totals['pending_commissions']) }}</p>
            <p class="stat-label">Pending Commissions</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">{{ number_format($totals['pending_payouts']) }}</p>
            <p class="stat-label">Pending Payouts</p>
        </div></div>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <div class="sb-search-bar mb-3">
            <form method="GET" action="{{ route('affiliates.index') }}" class="d-flex gap-2 flex-wrap" style="width: 100%;">
                <input type="text" name="search" class="sb-form-input" style="max-width: 300px;"
                       placeholder="Search by name, email, or code..." value="{{ request('search') }}">
                <select name="status" class="sb-form-select" style="width: auto;">
                    <option value="">All Statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                </select>
                <button type="submit" class="sb-btn sb-btn-primary sb-btn-sm">Filter</button>
                @if (request('search') || request('status'))
                    <a href="{{ route('affiliates.index') }}" class="sb-btn sb-btn-secondary sb-btn-sm">Clear</a>
                @endif
            </form>
        </div>

        @if ($affiliates->count() > 0)
            <div class="table-responsive">
                <table class="table sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Affiliate</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Referrals</th>
                            <th>Commissions</th>
                            <th>Pending Amount</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($affiliates as $affiliate)
                            <tr>
                                <td>
                                    <strong>{{ $affiliate->name }}</strong>
                                    <br><small class="text-muted">{{ $affiliate->email }}</small>
                                </td>
                                <td>
                                    <span class="sb-badge sb-badge-info">{{ $affiliate->code }}</span>
                                    <br><small class="text-muted">{{ number_format($affiliate->clicks) }} clicks</small>
                                </td>
                                <td>
                                    @php
                                        $badge = match ($affiliate->status) {
                                            'active' => 'sb-badge-active',
                                            'pending' => 'sb-badge-pending',
                                            'suspended' => 'sb-badge-inactive',
                                            default => 'sb-badge-info',
                                        };
                                    @endphp
                                    <span class="sb-badge {{ $badge }}">{{ ucfirst($affiliate->status) }}</span>
                                </td>
                                <td>{{ number_format($affiliate->referrals_count) }}</td>
                                <td>{{ number_format($affiliate->commissions_count) }}</td>
                                <td>₦{{ number_format((float) ($affiliate->pending_commission_sum ?? 0), 2) }}</td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <a href="{{ route('affiliates.show', $affiliate) }}" class="sb-btn sb-btn-outline-primary sb-btn-sm" title="View">View</a>
                                        @if ($affiliate->isPending() || $affiliate->isSuspended())
                                            <form method="POST" action="{{ route('affiliates.activate', $affiliate) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="sb-btn sb-btn-outline-success sb-btn-sm" title="Activate">Activate</button>
                                            </form>
                                        @endif
                                        @if ($affiliate->isActive())
                                            <form method="POST" action="{{ route('affiliates.suspend', $affiliate) }}" class="d-inline" onsubmit="return confirm('Suspend this affiliate? Their referral codes will stop working.');">
                                                @csrf
                                                <button type="submit" class="sb-btn sb-btn-outline-warning sb-btn-sm" title="Suspend">Suspend</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $affiliates->links() }}
            </div>
        @else
            <div class="sb-empty-state">
                <h5>No Affiliates Found</h5>
                <p>Affiliates appear here once they register through the public affiliate signup form.</p>
            </div>
        @endif
    </div>
</div>
@endsection
