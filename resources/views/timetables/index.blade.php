@extends('layouts.app')

@section('title', 'Timetables - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Timetables</h2>
            <p class="text-muted mb-0">Manage school class timetables</p>
        </div>
        <a href="{{ route('timetables.create') }}" class="sb-btn sb-btn-primary">+ Add Entry</a>
    </div>

    <div class="sb-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('timetables.index') }}" class="row g-2">
                <div class="col-12 col-sm-6 col-md-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="sb-form-input">
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="section_id" class="sb-form-select">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="teacher_id" class="sb-form-select">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="subject_id" class="sb-form-select">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="day" class="sb-form-select">
                        <option value="">All Days</option>
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
                    @if(request('search') || request('class_id') || request('section_id') || request('teacher_id') || request('day') || request('subject_id'))
                        <a href="{{ route('timetables.index') }}" class="sb-btn sb-btn-outline-secondary">Clear Filters</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="sb-card sb-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Day</th>
                            <th>Period</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Notes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timetables as $entry)
                            <tr>
                                <td class="fw-medium">{{ $entry->schoolClass->name ?? '—' }}</td>
                                <td>
                                    @if($entry->section)
                                        <span class="badge" style="background: #e7f1ff; color: #0d6efd;">
                                            {{ $entry->section->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
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
                                    <span class="badge" style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                        {{ $entry->day }}
                                    </span>
                                </td>
                                <td>
                                    @if($entry->period)
                                        <span class="fw-medium">{{ $entry->period->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $entry->period->start_time ? \Carbon\Carbon::parse($entry->period->start_time)->format('h:i A') : '' }} - {{ $entry->period->end_time ? \Carbon\Carbon::parse($entry->period->end_time)->format('h:i A') : '' }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="fw-medium">{{ $entry->subject->name ?? '—' }}</td>
                                <td>{{ $entry->teacher->full_name ?? '—' }}</td>
                                <td class="text-muted">{{ Str::limit($entry->notes ?? '—', 30) }}</td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <a href="{{ route('timetables.edit', $entry) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('timetables.destroy', $entry) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this timetable entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <p class="mb-2">No timetable entries found.</p>
                                    <a href="{{ route('timetables.create') }}" class="text-primary fw-medium text-decoration-none">Add your first timetable entry</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($timetables->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $timetables->links() }}
        </div>
    @endif
</div>
@endsection
