@extends('layouts.app')

@section('title', 'Schools - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Schools</h2>
            <p class="text-muted mb-0">Manage all registered schools</p>
        </div>
        <a href="{{ route('schools.create') }}" class="sb-btn sb-btn-primary">
            + Add School
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('schools.index') }}" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, email or slug..."
                    class="sb-form-input"
                >
                <button type="submit" class="sb-btn sb-btn-dark">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('schools.index') }}" class="sb-btn sb-btn-secondary">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                            <tr>
                                <td><strong>{{ $school->name }}</strong></td>
                                <td>
                                    <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $school->slug }}</code>
                                </td>
                                <td class="text-muted">{{ $school->email ?? '—' }}</td>
                                <td class="text-muted">{{ $school->phone ?? '—' }}</td>
                                <td>
                                    @if($school->is_active)
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <a href="{{ route('schools.edit', $school) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('schools.toggle-status', $school) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="sb-btn sb-btn-sm {{ $school->is_active ? 'sb-btn-outline-warning' : 'sb-btn-outline-success' }}">
                                                {{ $school->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('schools.destroy', $school) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this school?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No schools found.</p>
                                    <a href="{{ route('schools.create') }}" style="color: var(--primary); font-weight: 500; text-decoration: none;">Add your first school</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($schools->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $schools->links() }}
        </div>
    @endif
</div>
@endsection
