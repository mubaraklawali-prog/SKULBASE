@extends('layouts.app')

@section('title', 'Grading System - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Grading System</h2>
            <p class="text-muted mb-0">Manage grade ranges and scoring rules</p>
        </div>
        <a href="{{ route('results.grading-systems.create') }}" class="sb-btn sb-btn-primary">+ Add Rule</a>
    </div>

    <div class="sb-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.grading-systems.index') }}" class="sb-search-bar">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by grade, remark..." class="sb-form-input">
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                @if(request('search'))
                    <a href="{{ route('results.grading-systems.index') }}" class="sb-btn sb-btn-outline-secondary">Clear</a>
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
                            <th>Min Score (%)</th>
                            <th>Max Score (%)</th>
                            <th>Grade</th>
                            <th>Grade Point</th>
                            <th>Remark</th>
                            <th>School</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gradingSystems as $gradingSystem)
                            <tr>
                                <td class="fw-medium">{{ $gradingSystem->min_score }}%</td>
                                <td class="fw-medium">{{ $gradingSystem->max_score }}%</td>
                                <td class="text-muted fw-semibold">{{ $gradingSystem->grade }}</td>
                                <td class="text-muted">{{ $gradingSystem->grade_point ?? '—' }}</td>
                                <td class="text-muted">{{ $gradingSystem->remark }}</td>
                                <td class="text-muted">{{ $gradingSystem->school->name ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <a href="{{ route('results.grading-systems.edit', $gradingSystem) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('results.grading-systems.destroy', $gradingSystem) }}" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="sb-empty-state">
                                        <p>No grading rules found.</p>
                                        <a href="{{ route('results.grading-systems.create') }}" class="sb-link">Create your first rule</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($gradingSystems->hasPages())
        <div class="d-flex justify-content-center mt-3">{{ $gradingSystems->links() }}</div>
    @endif
</div>
@endsection
