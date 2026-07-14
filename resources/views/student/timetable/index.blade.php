@extends('layouts.app')

@section('title', 'My Timetable - Skulbase')

@section('content')
<style>
    .timetable-grid-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
    }
    .timetable-grid {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 900px;
    }
    .timetable-grid thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #0a1628;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 12px;
        text-align: center;
        white-space: nowrap;
    }
    .timetable-grid thead th:first-child {
        position: sticky;
        left: 0;
        z-index: 20;
        background: #0a1628;
        text-align: left;
        min-width: 160px;
    }
    .timetable-grid tbody td {
        padding: 10px 12px;
        vertical-align: top;
        border-bottom: 1px solid #f0f0f0;
        min-height: 80px;
        height: 80px;
        transition: background 0.15s;
    }
    .timetable-grid tbody tr:hover td {
        background: #f8f9ff;
    }
    .timetable-grid tbody td:first-child {
        position: sticky;
        left: 0;
        z-index: 5;
        background: #f8f9fa;
        font-weight: 600;
        font-size: 13px;
        color: #333;
        white-space: nowrap;
    }
    .timetable-grid tbody tr:hover td:first-child {
        background: #eef1f5;
    }
    .period-time {
        font-size: 11px;
        color: #6c757d;
        margin-top: 2px;
    }
    .lesson-card {
        background: #f0f7ff;
        border-left: 3px solid #4f9cf7;
        border-radius: 6px;
        padding: 8px 10px;
    }
    .lesson-card .subject-name {
        font-weight: 600;
        font-size: 13px;
        color: #1a1a2e;
        margin-bottom: 3px;
    }
    .lesson-card .teacher-name {
        font-size: 12px;
        color: #6c757d;
    }
    .lesson-card .room-info {
        font-size: 11px;
        color: #adb5bd;
        margin-top: 2px;
    }
    .break-badge, .lunch-badge, .assembly-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 56px;
    }
    .break-badge span {
        background: #fff3cd;
        color: #664d03;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .lunch-badge span {
        background: #d1e7dd;
        color: #0f5132;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .assembly-badge span {
        background: #f0d9ff;
        color: #6f42c1;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .free-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 56px;
        color: #ced4da;
        font-size: 13px;
        font-style: italic;
    }
    .day-header-mon { background: #1a5c3a !important; }
    .day-header-tue { background: #1a4a7a !important; }
    .day-header-wed { background: #7a5c1a !important; }
    .day-header-thu { background: #5c1a7a !important; }
    .day-header-fri { background: #7a1a2a !important; }
    .day-header-sat { background: #4a5568 !important; }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Timetable</h2>
            <p class="text-muted mb-0">{{ $student->full_name }} — {{ $schoolClass->name ?? '' }}{{ $section ? ' — ' . $section->name : '' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('student.timetable.print') }}"
               target="_blank"
               class="btn"
               style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print
            </a>
        </div>
    </div>

    <div class="card stat-card mb-3">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span style="background: #0a1628; color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    {{ $student->full_name }}
                </span>
                <span style="background: #e7f1ff; color: #0d6efd; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    {{ $schoolClass->name ?? '' }}{{ $section ? ' — ' . $section->name : '' }}
                </span>
                <span style="color: #6c757d; font-size: 13px;">
                    {{ $grid->count() }} lesson{{ $grid->count() !== 1 ? 's' : '' }} this week
                </span>
            </div>
        </div>
    </div>

    <div class="timetable-grid-wrapper">
        <table class="timetable-grid">
            <thead>
                <tr>
                    <th>Period</th>
                    @php
                        $dayHeaderClasses = [
                            'Monday' => 'day-header-mon',
                            'Tuesday' => 'day-header-tue',
                            'Wednesday' => 'day-header-wed',
                            'Thursday' => 'day-header-thu',
                            'Friday' => 'day-header-fri',
                            'Saturday' => 'day-header-sat',
                        ];
                    @endphp
                    @foreach($days as $day)
                        <th class="{{ $dayHeaderClasses[$day] ?? '' }}">{{ strtoupper(substr($day, 0, 3)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($periods as $period)
                    <tr>
                        <td>
                            <div>{{ $period->name }}</div>
                            @if($period->start_time && $period->end_time)
                                <div class="period-time">
                                    {{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}
                                </div>
                            @endif
                        </td>
                        @foreach($days as $day)
                            @php
                                $key = $period->id . '_' . $day;
                                $entry = $grid->get($key);
                            @endphp
                            <td>
                                @if(in_array($period->type, ['break', 'lunch', 'assembly']))
                                    <div class="{{ $period->type }}-badge">
                                        <span>{{ ucfirst($period->type) }}</span>
                                    </div>
                                @elseif($entry)
                                    <div class="lesson-card">
                                        <div class="subject-name">{{ $entry->subject->name ?? '—' }}</div>
                                        <div class="teacher-name">{{ $entry->teacher->full_name ?? '—' }}</div>
                                        @if($entry->notes)
                                            <div class="room-info">{{ \Illuminate\Support\Str::limit($entry->notes, 25) }}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="free-cell">Free Period</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($days) + 1 }}" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                            No timetable data available for your class.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
