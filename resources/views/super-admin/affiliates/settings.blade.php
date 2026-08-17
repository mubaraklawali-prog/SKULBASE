@extends('layouts.app')

@section('title', 'Affiliate Program Settings - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2>Affiliate Program Settings</h2>
            <p class="mb-0">Configure commission rates and payout rules</p>
        </div>
        <a href="{{ route('affiliates.index') }}" class="sb-btn sb-btn-outline-secondary sb-btn-sm">Back to Affiliates</a>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body" style="max-width: 640px;">
        <form method="POST" action="{{ route('affiliates.settings.update') }}" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="setting-rate" class="sb-form-label">Default Commission Rate (%)</label>
                <input type="number" id="setting-rate" name="default_commission_rate" class="sb-form-input"
                       min="0" max="100" step="0.01" value="{{ $settings['default_commission_rate'] }}" required>
                <small class="sb-form-help">Applied to all affiliates unless overridden per affiliate. Can be any value 0–100.</small>
                @error('default_commission_rate')
                    <div class="sb-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="setting-months" class="sb-form-label">Commission Months</label>
                <input type="number" id="setting-months" name="commission_months" class="sb-form-input"
                       min="1" max="60" value="{{ $settings['commission_months'] }}" required>
                <small class="sb-form-help">How long recurring commissions are paid after a referred school's first payment.</small>
                @error('commission_months')
                    <div class="sb-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="setting-min" class="sb-form-label">Minimum Payout Amount (₦)</label>
                <input type="number" id="setting-min" name="min_payout_amount" class="sb-form-input"
                       min="0" step="0.01" value="{{ $settings['min_payout_amount'] }}" required>
                @error('min_payout_amount')
                    <div class="sb-form-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="sb-btn sb-btn-primary">Save Settings</button>
        </form>
    </div>
</div>
@endsection
