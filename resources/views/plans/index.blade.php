@extends('layouts.app')

@section('title', 'Plans - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Pricing Plans</h2>
            <p class="text-muted mb-0">Manage subscription plans and pricing</p>
        </div>
        <a href="{{ route('plans.create') }}" class="sb-btn sb-btn-primary">
            + Add Plan
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('plans.index') }}" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or slug..."
                    class="sb-form-input"
                >
                <button type="submit" class="sb-btn sb-btn-dark">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('plans.index') }}" class="sb-btn sb-btn-secondary">
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
                            <th>Plan Name</th>
                            <th>Monthly Price</th>
                            <th>Yearly Price</th>
                            <th>Student Limit</th>
                            <th>Trial</th>
                            <th>Discount</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>
                                    <strong>{{ $plan->name }}</strong>
                                    <div style="font-size: 12px; color: #6c757d;">{{ $plan->slug }}</div>
                                </td>
                                <td>
                                    @if($plan->isDiscountActive() && in_array($plan->discount_scope, ['monthly', 'both']))
                                        <span style="text-decoration: line-through; color: #999; font-size: 12px;">{{ $plan->formattedMonthlyPrice() }}</span>
                                        <br>
                                        <strong style="color: #dc3545;">{{ $plan->formattedDiscountedMonthlyPrice() }}</strong>
                                    @else
                                        <strong>{{ $plan->formattedMonthlyPrice() }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->isDiscountActive() && in_array($plan->discount_scope, ['annual', 'both']))
                                        <span style="text-decoration: line-through; color: #999; font-size: 12px;">{{ $plan->formattedYearlyPrice() }}</span>
                                        <br>
                                        <strong style="color: #dc3545;">{{ $plan->formattedDiscountedYearlyPrice() }}</strong>
                                    @else
                                        <strong>{{ $plan->formattedYearlyPrice() }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->is_unlimited)
                                        <span class="sb-badge sb-badge-info">Unlimited</span>
                                    @else
                                        {{ number_format($plan->student_limit ?? 0) }}
                                    @endif
                                </td>
                                <td>{{ $plan->trial_days }}d</td>
                                <td>
                                    @if($plan->isDiscountActive())
                                        <span class="sb-badge sb-badge-active">{{ $plan->discount_percentage }}% off</span>
                                        <div style="font-size: 11px; color: #6c757d; margin-top: 2px;">{{ $plan->discount_scope_label }}</div>
                                    @else
                                        <span style="color: #999;">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <form method="POST" action="{{ route('plans.toggle-status', $plan) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="sb-btn sb-btn-sm {{ $plan->is_active ? 'sb-btn-outline-warning' : 'sb-btn-outline-success' }}">
                                                {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('plans.show', $plan) }}" class="sb-btn sb-btn-sm sb-btn-secondary d-md-inline-flex d-none">
                                            View
                                        </a>
                                        <a href="{{ route('plans.edit', $plan) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('plans.destroy', $plan) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this plan?');">
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
                                    <p style="margin: 0; font-size: 15px;">No plans found.</p>
                                    <a href="{{ route('plans.create') }}" style="color: var(--primary); font-weight: 500; text-decoration: none;">Add your first plan</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($plans->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $plans->links() }}
        </div>
    @endif
</div>
@endsection
