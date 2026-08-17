@extends('layouts.app')

@section('title', 'Affiliate Dashboard - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2>Welcome, {{ $affiliate->name }}</h2>
            <p class="mb-0">Track your referrals, commissions, and payouts</p>
        </div>
        <div>
            @if ($affiliate->isActive())
                <span class="sb-badge sb-badge-active">Active</span>
            @elseif ($affiliate->isSuspended())
                <span class="sb-badge sb-badge-inactive">Suspended</span>
            @else
                <span class="sb-badge sb-badge-pending">Pending Approval</span>
            @endif
        </div>
    </div>

    @if ($affiliate->isPending())
        <div class="sb-flash sb-flash-info mt-3">
            Your account is awaiting approval. Referrals only earn commissions once an admin activates your account.
        </div>
    @endif
</div>

{{-- Referral Link --}}
<div class="card stat-card mb-4">
    <div class="card-body">
        <h6 class="fw-semibold mb-1">Your Referral Link</h6>
        <p class="text-muted small mb-3">Share this link — when a school registers through it and pays, you earn a
            {{ $affiliate->effectiveCommissionRate() }}% commission on the first payment and every renewal for 12 months.</p>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <input type="text" id="referralLink" class="sb-form-input" style="max-width: 560px;" readonly
                   value="{{ $affiliate->referralUrl() }}">
            <button type="button" class="sb-btn sb-btn-primary sb-btn-sm" id="copyLinkBtn" onclick="copyReferralLink()">Copy Link</button>
            <button type="button" class="sb-btn sb-btn-outline-primary sb-btn-sm" onclick="navigator.clipboard.writeText('{{ $affiliate->code }}').then(() => alert('Referral code copied: {{ $affiliate->code }}'))">Copy Code</button>
            <span class="sb-form-help mb-0">Your code: <strong>{{ $affiliate->code }}</strong></span>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="stat-number">{{ number_format($stats['total_clicks']) }}</p>
                <p class="stat-label">Total Clicks</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="stat-number">{{ number_format($stats['referred_schools']) }}</p>
                <p class="stat-label">Referred Schools</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="stat-number">{{ number_format($stats['trial_referrals']) }}</p>
                <p class="stat-label">Trial Referrals</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="stat-number">{{ number_format($stats['paying_referrals']) }}</p>
                <p class="stat-label">Paying Referrals</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="stat-number">₦{{ number_format($stats['pending_commission'], 2) }}</p>
                <p class="stat-label">Pending Commission</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="stat-number">₦{{ number_format($stats['approved_commission'], 2) }}</p>
                <p class="stat-label">Approved Commission</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="stat-number">₦{{ number_format($stats['paid_commission'], 2) }}</p>
                <p class="stat-label">Paid Commission</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="stat-number" style="color: var(--success);">₦{{ number_format($stats['total_earned'], 2) }}</p>
                <p class="stat-label">Total Earned</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Referrals --}}
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
                                    <th>Referred</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($referrals as $referral)
                                    <tr>
                                        <td>
                                            <strong>{{ $referral->school->name ?? 'Pending' }}</strong>
                                            @if ($referral->referred_email)
                                                <br><small class="text-muted">{{ $referral->referred_email }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badge = match ($referral->status) {
                                                    'registered' => 'sb-badge-pending',
                                                    'approved' => 'sb-badge-info',
                                                    'converted' => 'sb-badge-active',
                                                    'expired' => 'sb-badge-inactive',
                                                    'cancelled' => 'sb-badge-inactive',
                                                    default => 'sb-badge-info',
                                                };
                                            @endphp
                                            <span class="sb-badge {{ $badge }}">{{ ucfirst($referral->status) }}</span>
                                        </td>
                                        <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $referrals->links() }}</div>
                @else
                    <div class="sb-empty-state">
                        <h5>No Referrals Yet</h5>
                        <p>Share your referral link to start earning commissions.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Commissions --}}
        <div class="card action-card mb-4">
            <div class="card-header">Commissions</div>
            <div class="card-body">
                @if ($commissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table sb-table mb-0">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Period</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commissions as $commission)
                                    <tr>
                                        <td>
                                            <strong>{{ $commission->referral->school->name ?? 'Unknown' }}</strong>
                                            <br><small class="text-muted">{{ ucwords(str_replace('_', ' ', $commission->type)) }} @ {{ $commission->formattedRate() }}</small>
                                        </td>
                                        <td>{{ $commission->paid_period }}</td>
                                        <td>{{ $commission->formattedAmount() }}</td>
                                        <td>
                                            <span class="sb-badge {{ $commission->status_badge }}">{{ ucfirst($commission->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $commissions->links() }}</div>
                @else
                    <div class="sb-empty-state">
                        <h5>No Commissions Yet</h5>
                        <p>Commissions appear when your referred schools start paying.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Request Payout --}}
        <div class="card action-card mb-4">
            <div class="card-header">Request Payout</div>
            <div class="card-body">
                @if ($affiliate->isActive())
                    <form method="POST" action="{{ route('affiliate.payouts.request') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="payout-amount" class="sb-form-label">Amount (₦)</label>
                            <input type="number" id="payout-amount" name="amount" class="sb-form-input"
                                   min="0.01" step="0.01" value="{{ old('amount', $stats['approved_commission'] + $stats['paid_commission'] > 0 ? $stats['approved_commission'] : '') }}"
                                   required>
                            <small class="sb-form-help">Minimum payout: ₦{{ number_format($minPayout, 2) }}</small>
                        </div>
                        <div class="mb-3">
                            <label for="payout-method" class="sb-form-label">Payout Method</label>
                            <input type="text" id="payout-method" name="method" class="sb-form-input"
                                   value="{{ old('method', $affiliate->payout_method) }}" placeholder="e.g. Bank Transfer" required>
                        </div>
                        <div class="mb-3">
                            <label for="payout-details" class="sb-form-label">Details</label>
                            <textarea id="payout-details" name="payout_details" class="sb-form-textarea" rows="3"
                                      placeholder="Account name, number, bank...">{{ old('payout_details') }}</textarea>
                        </div>
                        <button type="submit" class="sb-btn sb-btn-primary w-100">Request Payout</button>
                    </form>
                @else
                    <p class="text-muted small mb-0">Your account must be active to request a payout.</p>
                @endif
            </div>
        </div>

        {{-- Payout History --}}
        <div class="card action-card mb-4">
            <div class="card-header">Payout History</div>
            <div class="card-body">
                @if ($payouts->count() > 0)
                    <div class="table-responsive">
                        <table class="table sb-table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payouts as $payout)
                                    <tr>
                                        <td>{{ $payout->requested_at ? $payout->requested_at->format('M d, Y') : '-' }}</td>
                                        <td>{{ $payout->formattedAmount() }}</td>
                                        <td>
                                            <span class="sb-badge {{ $payout->status_badge }}">{{ ucfirst($payout->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted small mb-0">No payout requests yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function copyReferralLink() {
        var input = document.getElementById('referralLink');
        navigator.clipboard.writeText(input.value).then(function() {
            var btn = document.getElementById('copyLinkBtn');
            var original = btn.textContent;
            btn.textContent = 'Copied!';
            setTimeout(function() { btn.textContent = original; }, 1500);
        });
    }
</script>
@endsection
