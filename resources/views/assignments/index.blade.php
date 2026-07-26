@extends('layouts.app')

@section('title', 'Assignments - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Assignments</h2>
            <p class="text-muted mb-0">Manage homework and class assignments</p>
        </div>
        <a href="{{ route('assignments.create') }}" class="sb-btn sb-btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            New Assignment
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('assignments.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="sb-form-label">Search</label>
                    <input type="text" name="search" class="sb-form-input" value="{{ request('search') }}" placeholder="Search by title...">
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Class</label>
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Subject</label>
                    <select name="subject_id" class="sb-form-select">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Status</label>
                    <select name="status" class="sb-form-select">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="sb-btn sb-btn-primary w-100">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'class_id', 'subject_id', 'status']))
                    <div class="col-md-1">
                        <a href="{{ route('assignments.index') }}" class="sb-btn sb-btn-secondary w-100 text-center">Clear</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover sb-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td>
                                    <a href="{{ route('assignments.show', $assignment) }}" style="color: #0a1628; font-weight: 600; text-decoration: none;">{{ $assignment->title }}</a>
                                    @if($assignment->total_marks)
                                        <br><small class="text-muted">{{ $assignment->total_marks }} marks</small>
                                    @endif
                                </td>
                                <td>{{ $assignment->schoolClass->name ?? '—' }}</td>
                                <td>{{ $assignment->subject->name ?? '—' }}</td>
                                <td>{{ $assignment->teacher->full_name ?? '—' }}</td>
                                <td>
                                    <span class="{{ $assignment->due_date->isPast() ? 'text-danger' : 'text-muted' }}">
                                        {{ $assignment->due_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($assignment->status === 'published')
                                        <span class="sb-badge sb-badge-published">Published</span>
                                    @else
                                        <span class="sb-badge sb-badge-draft">Draft</span>
                                    @endif
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <a href="{{ route('assignments.show', $assignment) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                    <a href="{{ route('assignments.edit', $assignment) }}" class="sb-btn sb-btn-sm sb-btn-secondary">Edit</a>
                                    <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No assignments found. <a href="{{ route('assignments.create') }}" style="color: var(--primary);">Create your first assignment</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($assignments->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $assignments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
