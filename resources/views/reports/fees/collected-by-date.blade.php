@extends('layouts.app')

@section('title', 'Fees Collected by Date - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Fees Collected by Date</h2>
            <p class="text-muted mb-0">Daily collection summary</p>
        </div>
        <a href="{{ route('reports.dashboard') }}" class="sb-btn sb-btn-secondary">Back</a>
    </div>

    <form method="GET" action="{{ route('reports.fees.collected') }}" class="card stat-card mb-4">
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label class="sb-form-label">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="sb-form-input">
                </div>
                <div style="flex: 1;">
                    <label class="sb-form-label">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="sb-form-input">
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">₦{{ number_format($grand_total, 2) }}</p>
                    <p class="stat-label">Grand Total Collected</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #0d6efd;">{{ count($days) }}</p>
                    <p class="stat-label">Days with Collections</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #664d03;">{{ collect($days)->sum('count') }}</p>
                    <p class="stat-label">Total Transactions</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Transactions</th>
                            <th>Cash</th>
                            <th>Transfer</th>
                            <th>Card</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($days as $day)
                            <tr>
                                <td style="font-weight: 500;">{{ \Carbon\Carbon::parse($day['date'])->format('l, M d, Y') }}</td>
                                <td>{{ $day['count'] }}</td>
                                <td>₦{{ number_format($day['by_method']['cash']['total'] ?? 0, 2) }}</td>
                                <td>₦{{ number_format($day['by_method']['transfer']['total'] ?? 0, 2) }}</td>
                                <td>₦{{ number_format($day['by_method']['card']['total'] ?? 0, 2) }}</td>
                                <td style="font-weight: 600; color: #0f5132;">₦{{ number_format($day['total'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="sb-empty-state">No collection data found for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
