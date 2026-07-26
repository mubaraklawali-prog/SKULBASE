@extends('layouts.app')

@section('title', 'Attendance Records - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Attendance Records</h2>
            <p class="text-muted mb-0">Browse and filter all attendance records</p>
        </div>
        <a href="{{ route('attendance.create') }}" class="sb-btn sb-btn-primary">Take Attendance</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.index') }}" class="sb-search-bar">
                <input type="date" name="date" value="{{ request('date') }}" max="{{ date('Y-m-d') }}" placeholder="Date" class="sb-form-input">
                <select name="class_id" class="sb-form-select">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="sb-form-select">
                    <option value="">All Statuses</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                    <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                    <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>Excused</option>
                </select>
                <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
                @if(request()->hasAny(['date', 'class_id', 'status']))
                    <a href="{{ route('attendance.index') }}" class="sb-btn sb-btn-secondary">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Marked By</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $record)
                            <tr>
                                <td>{{ $record->attendance_date->format('M d, Y') }}</td>
                                <td style="font-weight: 500;">
                                    <a href="{{ route('attendance.student', $record->student) }}" style="color: #333; text-decoration: none;">{{ $record->student->full_name }}</a>
                                </td>
                                <td>{{ $record->schoolClass->name ?? '—' }}</td>
                                <td>
                                    @if($record->status === 'present')
                                        <span class="sb-badge sb-badge-present">Present</span>
                                    @elseif($record->status === 'absent')
                                        <span class="sb-badge sb-badge-absent">Absent</span>
                                    @elseif($record->status === 'late')
                                        <span class="sb-badge sb-badge-late">Late</span>
                                    @else
                                        <span class="sb-badge sb-badge-excused">Excused</span>
                                    @endif
                                </td>
                                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $record->remarks ?? '—' }}</td>
                                <td>{{ $record->marker->full_name ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('attendance.show', $record) }}" style="color: var(--primary); font-weight: 500; text-decoration: none; font-size: 13px;">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="sb-empty-state">
                                        <p style="margin: 0; font-size: 15px;">No attendance records found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($attendances->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
@endsection
