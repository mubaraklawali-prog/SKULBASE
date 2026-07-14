@extends('layouts.app')

@section('title', 'Assignments - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Assignments</h2>
            <p class="text-muted mb-0">Manage homework and class assignments</p>
        </div>
        <a href="{{ route('assignments.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            New Assignment
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background: #d1e7dd; border-color: #badbcc; color: #0f5132;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('assignments.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by title..." style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Class</label>
                    <select name="class_id" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Subject</label>
                    <select name="subject_id" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: #6c757d; margin-bottom: 4px;">Status</label>
                    <select name="status" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer; width: 100%;">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'class_id', 'subject_id', 'status']))
                    <div class="col-md-1">
                        <a href="{{ route('assignments.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 16px; font-weight: 500; text-decoration: none; width: 100%; text-align: center;">Clear</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" style="margin-bottom: 0;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Title</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Class</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Subject</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Teacher</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Due Date</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px;">Status</th>
                            <th style="font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 12px;">
                                    <a href="{{ route('assignments.show', $assignment) }}" style="color: #0a1628; font-weight: 600; text-decoration: none;">{{ $assignment->title }}</a>
                                    @if($assignment->total_marks)
                                        <br><small style="color: #6c757d;">{{ $assignment->total_marks }} marks</small>
                                    @endif
                                </td>
                                <td style="padding: 12px;">{{ $assignment->schoolClass->name ?? '—' }}</td>
                                <td style="padding: 12px;">{{ $assignment->subject->name ?? '—' }}</td>
                                <td style="padding: 12px;">{{ $assignment->teacher->full_name ?? '—' }}</td>
                                <td style="padding: 12px;">
                                    <span style="font-size: 13px; {{ $assignment->due_date->isPast() ? 'color: #dc3545;' : 'color: #6c757d;' }}">
                                        {{ $assignment->due_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td style="padding: 12px;">
                                    @if($assignment->status === 'published')
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Published</span>
                                    @else
                                        <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Draft</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: right; white-space: nowrap;">
                                    <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; text-decoration: none;">View</a>
                                    <a href="{{ route('assignments.edit', $assignment) }}" class="btn btn-sm" style="background: #f0f2f5; color: #333; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; text-decoration: none;">Edit</a>
                                    <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #dc3545; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; border: none; cursor: pointer;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No assignments found. <a href="{{ route('assignments.create') }}" style="color: #4f9cf7;">Create your first assignment</a>.
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
