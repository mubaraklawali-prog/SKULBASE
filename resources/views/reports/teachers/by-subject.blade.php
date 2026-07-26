@extends('layouts.app')

@section('title', 'Teachers by Subject - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Teachers by Subject</h2>
            <p class="text-muted mb-0">Subject-wise teacher distribution</p>
        </div>
        <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="sb-btn sb-btn-secondary">Back</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">{{ $statusBreakdown['active'] }}</p>
                    <p class="stat-label">Active Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">{{ $statusBreakdown['inactive'] }}</p>
                    <p class="stat-label">Inactive Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #0d6efd;">{{ $statusBreakdown['total'] }}</p>
                    <p class="stat-label">Total Teachers</p>
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
                            <th>#</th>
                            <th>Subject</th>
                            <th>Teachers Assigned</th>
                            <th>Distribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maxCount = $bySubject->max('teachers_count') ?: 1; @endphp
                        @forelse($bySubject as $index => $subject)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="font-weight: 500;">{{ $subject->name }}</td>
                                <td style="font-weight: 600;">{{ $subject->teachers_count }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="flex: 1; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ ($subject->teachers_count / $maxCount) * 100 }}%; background: var(--primary); border-radius: 4px;"></div>
                                        </div>
                                        <span style="font-size: 12px; font-weight: 600; min-width: 35px;">{{ $subject->teachers_count }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="sb-empty-state">No subjects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
