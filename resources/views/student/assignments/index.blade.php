@extends('layouts.app')

@section('title', 'My Assignments - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Assignments</h2>
            <p class="text-muted mb-0">{{ $assignments->count() }} assignment{{ $assignments->count() !== 1 ? 's' : '' }} total</p>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            @php
                                $isOverdue = $assignment->due_date && $assignment->due_date->isPast();
                                $status = $isOverdue ? 'overdue' : 'upcoming';
                                $statusStyles = [
                                    'upcoming' => 'background: #d1e7dd; color: #0f5132;',
                                    'overdue' => 'background: #f8d7da; color: #842029;',
                                ];
                            @endphp
                            <tr>
                                <td><strong>{{ $assignment->title }}</strong></td>
                                <td>{{ $assignment->subject->name ?? '—' }}</td>
                                <td>{{ $assignment->teacher->first_name ?? '' }} {{ $assignment->teacher->last_name ?? '' }}</td>
                                <td style="white-space: nowrap;">{{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : '—' }}</td>
                                <td>
                                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; {{ $statusStyles[$status] ?? '' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('student.assignments.show', $assignment) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                    <p style="margin: 0;">No assignments found for your class.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
