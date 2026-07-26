@extends('layouts.app')

@section('title', "My Child's Assignments - Skulbase")

@section('content')
<style>
    .child-selector .form-check {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .child-selector .form-check:hover {
        border-color: var(--primary);
        background: #f8f9ff;
    }
    .child-selector .form-check-input:checked + .form-check-label {
        font-weight: 600;
        color: #0a1628;
    }
    .child-selector .form-check:has(.form-check-input:checked) {
        border-color: var(--primary);
        background: #f0f7ff;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-upcoming {
        background: #e7f1ff;
        color: #0d6efd;
    }
    .status-submitted {
        background: #d1e7dd;
        color: #0f5132;
    }
    .status-overdue {
        background: #f8d7da;
        color: #842029;
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Child's Assignments</h2>
            <p class="text-muted mb-0">View and track your child's assignments</p>
        </div>
    </div>

    @if($children->count() > 1)
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h6 style="font-weight: 600; margin-bottom: 12px; color: #0a1628;">Select Child</h6>
                <form method="GET" action="{{ route('parent.assignments.index') }}" class="child-selector">
                    <div class="row g-2">
                        @foreach($children as $child)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="student_id" id="child_{{ $child->id }}" value="{{ $child->id }}" {{ old('student_id', $selectedStudentId) == $child->id ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label" for="child_{{ $child->id }}">
                                        <strong>{{ $child->full_name }}</strong>
                                        <br><small style="color: #6c757d;">{{ $child->schoolClass->name ?? '' }}{{ $child->section ? ' — ' . $child->section->name : '' }}</small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($selectedStudent)
        <div class="card stat-card mb-3">
            <div class="card-body py-3 px-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span style="background: #0a1628; color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                            {{ $selectedStudent->full_name }}
                        </span>
                        <span style="color: #6c757d; font-size: 13px;">
                            {{ $assignments->count() }} assignment{{ $assignments->count() !== 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="font-weight: 600; color: #0a1628;">Title</th>
                                <th style="font-weight: 600; color: #0a1628;">Subject</th>
                                <th style="font-weight: 600; color: #0a1628;">Teacher</th>
                                <th style="font-weight: 600; color: #0a1628;">Due Date</th>
                                <th style="font-weight: 600; color: #0a1628;">Status</th>
                                <th style="font-weight: 600; color: #0a1628;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                                <tr>
                                    <td style="font-weight: 500;">{{ $assignment->title }}</td>
                                    <td>{{ $assignment->subject->name ?? '—' }}</td>
                                    <td>{{ $assignment->teacher->full_name ?? '—' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</td>
                                    <td>
                                        @php
                                            $status = strtolower($assignment->status ?? 'upcoming');
                                            $statusClass = match($status) {
                                                'submitted' => 'status-submitted',
                                                'overdue' => 'status-overdue',
                                                default => 'status-upcoming',
                                            };
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('parent.assignments.show', $assignment->id) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                        No assignments found for this student.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px 20px; text-align: center;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">Select a Child</h5>
                <p style="color: #adb5bd; margin: 0;">Choose a child above to view their assignments.</p>
            </div>
        </div>
    @endif
</div>
@endsection