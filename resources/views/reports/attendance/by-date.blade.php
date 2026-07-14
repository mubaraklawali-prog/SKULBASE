@extends('layouts.app')

@section('title', 'Attendance by Date - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Attendance by Date</h2>
            <p class="text-muted mb-0">Daily attendance breakdown</p>
        </div>
        <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back</a>
    </div>

    <form method="GET" action="{{ route('reports.attendance.by-date') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                </div>
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">Filter</button>
            </div>
        </div>
    </form>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Total</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Present</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Absent</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Late</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Excused</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dates as $day)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ \Carbon\Carbon::parse($day['date'])->format('l, M d, Y') }}</td>
                                <td style="padding: 14px 20px;">{{ $day['total'] }}</td>
                                <td style="padding: 14px 20px; color: #0f5132; font-weight: 600;">{{ $day['present'] }}</td>
                                <td style="padding: 14px 20px; color: #842029;">{{ $day['absent'] }}</td>
                                <td style="padding: 14px 20px; color: #664d03;">{{ $day['late'] }}</td>
                                <td style="padding: 14px 20px; color: #0d6efd;">{{ $day['excused'] }}</td>
                                <td style="padding: 14px 20px;">
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
                                <td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">No attendance data found for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
