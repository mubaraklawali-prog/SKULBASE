@extends('layouts.app')

@section('title', 'Student Status Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Student Status Breakdown</h2>
            <p class="text-muted mb-0">Enrollment status and gender distribution</p>
        </div>
        <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 30px;">
                    <p class="stat-number" style="font-size: 32px; color: #0d6efd;">{{ number_format($breakdown['total']) }}</p>
                    <p class="stat-label">Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body">
                    <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">By Status</h6>
                    @foreach($breakdown['by_status'] as $status => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-weight: 500; text-transform: capitalize;">{{ $status }}</span>
                            <span style="font-weight: 600;">{{ number_format($count) }}</span>
                        </div>
                        <div style="height: 6px; background: #e9ecef; border-radius: 3px; margin-bottom: 12px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $breakdown['total'] > 0 ? ($count / $breakdown['total']) * 100 : 0 }}%; background: {{ $status === 'active' ? '#198754' : '#dc3545' }}; border-radius: 3px;"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body">
                    <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">By Gender</h6>
                    @foreach($breakdown['by_gender'] as $gender => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-weight: 500; text-transform: capitalize;">{{ $gender }}</span>
                            <span style="font-weight: 600;">{{ number_format($count) }}</span>
                        </div>
                        <div style="height: 6px; background: #e9ecef; border-radius: 3px; margin-bottom: 12px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $breakdown['total'] > 0 ? ($count / $breakdown['total']) * 100 : 0 }}%; background: {{ $gender === 'male' ? '#0d6efd' : '#d63384' }}; border-radius: 3px;"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
