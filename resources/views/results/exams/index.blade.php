@extends('layouts.app')

@section('title', 'Exams - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Exams</h2>
            <p class="text-muted mb-0">Manage exams and examination schedules</p>
        </div>
        <a href="{{ route('results.exams.create') }}" class="sb-btn sb-btn-primary">+ Add Exam</a>
    </div>

    <div class="sb-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.exams.index') }}" class="sb-search-bar">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, term, session..." class="sb-form-input">
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                @if(request('search'))
                    <a href="{{ route('results.exams.index') }}" class="sb-btn sb-btn-outline-secondary">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="sb-card sb-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Term</th>
                            <th>Session</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>School</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td class="fw-medium">{{ $exam->name }}</td>
                                <td class="text-muted">{{ $exam->term ?? '—' }}</td>
                                <td class="text-muted">{{ $exam->session ?? '—' }}</td>
                                <td class="text-muted">{{ $exam->start_date ? $exam->start_date->format('M d, Y') : '—' }}</td>
                                <td class="text-muted">{{ $exam->end_date ? $exam->end_date->format('M d, Y') : '—' }}</td>
                                <td class="text-muted">{{ $exam->school->name ?? '—' }}</td>
                                <td>
                                    @if($exam->status)
                                        <span class="sb-badge sb-badge-present">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-absent">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <form method="POST" action="{{ route('results.exams.toggle-status', $exam) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="sb-btn sb-btn-sm {{ $exam->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success' }}">{{ $exam->status ? 'Deactivate' : 'Activate' }}</button>
                                        </form>
                                        <a href="{{ route('results.exams.edit', $exam) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('results.exams.destroy', $exam) }}" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="sb-empty-state">
                                        <p>No exams found.</p>
                                        <a href="{{ route('results.exams.create') }}" class="sb-link">Create your first exam</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($exams->hasPages())
        <div class="d-flex justify-content-center mt-3">{{ $exams->links() }}</div>
    @endif
</div>
@endsection
