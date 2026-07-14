@extends('layouts.app')

@section('title', 'Teacher Status Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Teacher Status Breakdown</h2>
            <p class="text-muted mb-0">Active vs inactive teachers</p>
        </div>
        <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 30px;">
                    <p class="stat-number" style="font-size: 32px; color: #0d6efd;">{{ $statusBreakdown['total'] }}</p>
                    <p class="stat-label">Total Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 30px;">
                    <p class="stat-number" style="font-size: 32px; color: #0f5132;">{{ $statusBreakdown['active'] }}</p>
                    <p class="stat-label">Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 30px;">
                    <p class="stat-number" style="font-size: 32px; color: #842029;">{{ $statusBreakdown['inactive'] }}</p>
                    <p class="stat-label">Inactive</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 style="font-weight: 600; margin-bottom: 16px;">Active vs Inactive</h6>
                    @if($statusBreakdown['total'] > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #0f5132; font-weight: 600;">Active</span>
                            <span style="font-weight: 600;">{{ $statusBreakdown['active'] }} ({{ round(($statusBreakdown['active'] / $statusBreakdown['total']) * 100, 1) }}%)</span>
                        </div>
                        <div style="height: 8px; background: #e9ecef; border-radius: 4px; margin-bottom: 16px; overflow: hidden;">
                            <div style="height: 100%; width: {{ ($statusBreakdown['active'] / $statusBreakdown['total']) * 100 }}%; background: #198754; border-radius: 4px;"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #842029; font-weight: 600;">Inactive</span>
                            <span style="font-weight: 600;">{{ $statusBreakdown['inactive'] }} ({{ round(($statusBreakdown['inactive'] / $statusBreakdown['total']) * 100, 1) }}%)</span>
                        </div>
                        <div style="height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: {{ ($statusBreakdown['inactive'] / $statusBreakdown['total']) * 100 }}%; background: #dc3545; border-radius: 4px;"></div>
                        </div>
                    @else
                        <p class="text-muted">No teacher data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
