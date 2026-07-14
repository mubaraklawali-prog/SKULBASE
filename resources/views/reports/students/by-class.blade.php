@extends('layouts.app')

@section('title', 'Students by Class - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Students by Class</h2>
            <p class="text-muted mb-0">Class-wise student distribution</p>
        </div>
        <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #0d6efd;">{{ number_format($statusBreakdown['total']) }}</p>
                    <p class="stat-label">Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">{{ number_format($statusBreakdown['by_status']['active'] ?? 0) }}</p>
                    <p class="stat-label">Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">{{ number_format($statusBreakdown['by_status']['inactive'] ?? 0) }}</p>
                    <p class="stat-label">Inactive</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #664d03;">{{ number_format($statusBreakdown['by_gender']['male'] ?? 0) }} / {{ number_format($statusBreakdown['by_gender']['female'] ?? 0) }}</p>
                    <p class="stat-label">Male / Female</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class Name</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Active Students</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Distribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maxCount = $byClass->max('students_count') ?: 1; @endphp
                        @forelse($byClass as $index => $class)
                            <tr>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $index + 1 }}</td>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $class->name }}</td>
                                <td style="padding: 14px 20px; font-weight: 600;">{{ $class->students_count }}</td>
                                <td style="padding: 14px 20px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="flex: 1; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ ($class->students_count / $maxCount) * 100 }}%; background: #4f9cf7; border-radius: 4px;"></div>
                                        </div>
                                        <span style="font-size: 12px; font-weight: 600; min-width: 35px;">{{ $class->students_count }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 40px 20px; text-align: center; color: #6c757d;">No classes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
