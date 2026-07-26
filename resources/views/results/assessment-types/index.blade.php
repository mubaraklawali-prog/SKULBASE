@extends('layouts.app')

@section('title', 'Assessment Types - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Assessment Types</h2>
            <p class="text-muted mb-0">Manage assessment categories and their weights</p>
        </div>
        <a href="{{ route('results.assessment-types.create') }}" class="sb-btn sb-btn-primary">+ Add Assessment Type</a>
    </div>

    <div class="sb-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.assessment-types.index') }}" class="sb-search-bar">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." class="sb-form-input">
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                @if(request('search'))
                    <a href="{{ route('results.assessment-types.index') }}" class="sb-btn sb-btn-outline-secondary">Clear</a>
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
                            <th>Percentage</th>
                            <th>School</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assessmentTypes as $type)
                            <tr>
                                <td class="fw-medium">{{ $type->name }}</td>
                                <td>
                                    <span class="sb-badge sb-badge-excused">{{ number_format($type->percentage, 1) }}%</span>
                                </td>
                                <td class="text-muted">{{ $type->school->name ?? '—' }}</td>
                                <td>
                                    @if($type->status)
                                        <span class="sb-badge sb-badge-present">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-absent">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <form method="POST" action="{{ route('results.assessment-types.toggle-status', $type) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="sb-btn sb-btn-sm {{ $type->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success' }}">{{ $type->status ? 'Deactivate' : 'Activate' }}</button>
                                        </form>
                                        <a href="{{ route('results.assessment-types.edit', $type) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('results.assessment-types.destroy', $type) }}" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="sb-empty-state">
                                        <p>No assessment types found.</p>
                                        <a href="{{ route('results.assessment-types.create') }}" class="sb-link">Create your first assessment type</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($assessmentTypes->hasPages())
        <div class="d-flex justify-content-center mt-3">{{ $assessmentTypes->links() }}</div>
    @endif
</div>
@endsection
