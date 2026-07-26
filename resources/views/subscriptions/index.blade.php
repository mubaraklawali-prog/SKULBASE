@extends('layouts.app')

@section('title', 'Subscriptions - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Subscriptions</h2>
            <p class="text-muted mb-0">Manage all school subscriptions</p>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('subscriptions.index') }}" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by school name..."
                    class="sb-form-input"
                >
                <select name="status" class="sb-form-select" style="width: auto; min-width: 140px;">
                    <option value="">All Status</option>
                    <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>Trial</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="grace" {{ request('status') === 'grace' ? 'selected' : '' }}>Grace</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <select name="plan_id" class="sb-form-select" style="width: auto; min-width: 140px;">
                    <option value="">All Plans</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
                <select name="billing_cycle" class="sb-form-select" style="width: auto; min-width: 140px;">
                    <option value="">All Cycles</option>
                    <option value="monthly" {{ request('billing_cycle') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ request('billing_cycle') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
                <select name="is_trial" class="sb-form-select" style="width: auto; min-width: 140px;">
                    <option value="">All Types</option>
                    <option value="1" {{ request('is_trial') === '1' ? 'selected' : '' }}>Trial</option>
                    <option value="0" {{ request('is_trial') === '0' ? 'selected' : '' }}>Paid</option>
                </select>
                <button type="submit" class="sb-btn sb-btn-dark">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'plan_id', 'billing_cycle', 'is_trial']))
                    <a href="{{ route('subscriptions.index') }}" class="sb-btn sb-btn-secondary">
                        Clear
                    </a>
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
                            <th>School</th>
                            <th>Plan</th>
                            <th>Billing</th>
                            <th>Status</th>
                            <th>Trial</th>
                            <th>Amount Paid</th>
                            <th>Expires</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($query as $subscription)
                            <tr>
                                <td>
                                    <strong>{{ $subscription->school->name ?? '—' }}</strong>
                                    <div style="font-size: 12px; color: #6c757d;">{{ $subscription->school->slug ?? '' }}</div>
                                </td>
                                <td><strong>{{ $subscription->plan->name ?? '—' }}</strong></td>
                                <td>
                                    <span class="sb-badge sb-badge-class">
                                        {{ ucfirst($subscription->billing_cycle) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="sb-badge {{ $subscription->status_badge }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($subscription->is_trial)
                                        <span class="sb-badge sb-badge-info">Yes</span>
                                    @else
                                        <span class="text-muted">No</span>
                                    @endif
                                </td>
                                <td>{{ $subscription->formattedAmountPaid() }}</td>
                                <td>
                                    @if($subscription->expires_at)
                                        {{ $subscription->expires_at->format('d M Y') }}
                                    @elseif($subscription->trial_ends_at)
                                        {{ $subscription->trial_ends_at->format('d M Y') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <a href="{{ route('subscriptions.show', $subscription) }}" class="sb-btn sb-btn-sm sb-btn-secondary">
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('subscriptions.destroy', $subscription) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this subscription record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No subscriptions found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($query->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $query->links() }}
        </div>
    @endif
</div>
@endsection
