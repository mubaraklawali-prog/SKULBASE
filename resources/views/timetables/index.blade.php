@extends('layouts.app')

@section('title', 'Timetables - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Timetables</h2>
            <p class="text-muted mb-0">Manage school class timetables</p>
        </div>
        <a href="{{ route('timetables.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            + Add Entry
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('timetables.index') }}" class="row g-2">
                <div class="col-md-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search..."
                        class="form-control"
                        style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                    >
                </div>
                <div class="col-md-2">
                    <select name="class_id" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="section_id" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="teacher_id" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="subject_id" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="day" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Days</option>
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">
                        Filter
                    </button>
                    @if(request('search') || request('class_id') || request('section_id') || request('teacher_id') || request('day') || request('subject_id'))
                        <a href="{{ route('timetables.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Section</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Day</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Period</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Subject</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Teacher</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Notes</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timetables as $entry)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $entry->schoolClass->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($entry->section)
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            {{ $entry->section->name }}
                                        </span>
                                    @else
                                        <span style="color: #adb5bd;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px;">
                                    @php
                                        $dayColors = [
                                            'Monday' => ['bg' => '#d1e7dd', 'text' => '#0f5132'],
                                            'Tuesday' => ['bg' => '#e7f1ff', 'text' => '#0d6efd'],
                                            'Wednesday' => ['bg' => '#fff3cd', 'text' => '#664d03'],
                                            'Thursday' => ['bg' => '#f0d9ff', 'text' => '#6f42c1'],
                                            'Friday' => ['bg' => '#f8d7da', 'text' => '#842029'],
                                            'Saturday' => ['bg' => '#f0f2f5', 'text' => '#6c757d'],
                                        ];
                                        $colors = $dayColors[$entry->day] ?? $dayColors['Monday'];
                                    @endphp
                                    <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        {{ $entry->day }}
                                    </span>
                                </td>
                                <td style="padding: 14px 20px;">
                                    @if($entry->period)
                                        <span style="font-weight: 500;">{{ $entry->period->name }}</span>
                                        <br>
                                        <small style="color: #6c757d;">{{ $entry->period->start_time ? \Carbon\Carbon::parse($entry->period->start_time)->format('h:i A') : '' }} - {{ $entry->period->end_time ? \Carbon\Carbon::parse($entry->period->end_time)->format('h:i A') : '' }}</small>
                                    @else
                                        <span style="color: #adb5bd;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $entry->subject->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">{{ $entry->teacher->full_name ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d; max-width: 150px;">
                                    {{ Str::limit($entry->notes ?? '—', 30) }}
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('timetables.edit', $entry) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('timetables.destroy', $entry) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this timetable entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #842029; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No timetable entries found.</p>
                                    <a href="{{ route('timetables.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Add your first timetable entry</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($timetables->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $timetables->links() }}
        </div>
    @endif
</div>
@endsection
