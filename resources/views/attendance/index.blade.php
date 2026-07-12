@extends('layouts.app')

@section('title', 'Attendance Records - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Attendance Records</h2>
            <p class="text-muted mb-0">Browse and filter all attendance records</p>
        </div>
        <a href="{{ route('attendance.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            Take Attendance
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.index') }}" class="d-flex gap-2 flex-wrap">
                <input type="date" name="date" value="{{ request('date') }}" max="{{ date('Y-m-d') }}" placeholder="Date" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                <select name="class_id" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                        </option>
                    @endforeach
                </select>
                <select name="status" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    <option value="">All Statuses</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                    <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                    <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>Excused</option>
                </select>
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer;">
                    Filter
                </button>
                @if(request()->hasAny(['date', 'class_id', 'status']))
                    <a href="{{ route('attendance.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Remarks</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Marked By</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $record)
                            <tr>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $record->attendance_date->format('M d, Y') }}</td>
                                <td style="padding: 14px 20px; font-weight: 500;">
                                    <a href="{{ route('attendance.student', $record->student) }}" style="color: #333; text-decoration: none;">{{ $record->student->full_name }}</a>
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $record->schoolClass->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($record->status === 'present')
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Present</span>
                                    @elseif($record->status === 'absent')
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Absent</span>
                                    @elseif($record->status === 'late')
                                        <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Late</span>
                                    @else
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Excused</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $record->remarks ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $record->marker->full_name ?? '—' }}</td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <a href="{{ route('attendance.show', $record) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No attendance records found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($attendances->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
@endsection
