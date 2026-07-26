@extends('layouts.app')

@section('title', 'Attendance by Date - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Attendance by Date</h2>
            <p class="text-muted mb-0">Daily attendance breakdown</p>
        </div>
        <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="sb-btn sb-btn-secondary">Back</a>
    </div>

    <form method="GET" action="{{ route('reports.attendance.by-date') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
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

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Late</th>
                            <th>Excused</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dates as $day)
                            <tr>
                                <td style="font-weight: 500;">{{ \Carbon\Carbon::parse($day['date'])->format('l, M d, Y') }}</td>
                                <td>{{ $day['total'] }}</td>
                                <td style="color: #0f5132; font-weight: 600;">{{ $day['present'] }}</td>
                                <td style="color: #842029;">{{ $day['absent'] }}</td>
                                <td style="color: #664d03;">{{ $day['late'] }}</td>
                                <td style="color: #0d6efd;">{{ $day['excused'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="flex: 1; height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ $day['rate'] }}%; background: {{ $day['rate'] >= 70 ? '#198754' : '#dc3545' }}; border-radius: 3px;"></div>
                                        </div>
                                        <span style="font-size: 12px; font-weight: 600; min-width: 40px;">{{ $day['rate'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="sb-empty-state">No attendance data found for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
