@extends('layouts.app')

@section('title', 'Classes - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Classes</h2>
            <p>Manage all classes and sections</p>
        </div>
        <a href="{{ route('classes.create') }}" class="sb-btn sb-btn-primary">+ Add Class</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('classes.index') }}" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or section..."
                    class="sb-form-input"
                    style="max-width: 400px;"
                >
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                @if(request('search'))
                    <a href="{{ route('classes.index') }}" class="sb-btn sb-btn-secondary">Clear</a>
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
                            <th>Section</th>
                            <th>School</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schoolClasses as $class)
                            <tr>
                                <td>
                                    <a href="{{ route('classes.show', $class) }}" style="color: #333; text-decoration: none; font-weight: 500;">
                                        {{ $class->name }}
                                    </a>
                                </td>
                                <td style="color: #6c757d;">{{ $class->section ?? '—' }}</td>
                                <td style="color: #6c757d;">{{ $class->school->name ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('classes.show', $class) }}" style="text-decoration: none;">
                                        <span class="sb-badge sb-badge-info">
                                            {{ $class->students_count }} {{ Str::plural('student', $class->students_count) }}
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    @if($class->status)
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <form method="POST" action="{{ route('classes.toggle-status', $class) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="sb-btn sb-btn-sm {{ $class->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success' }}">
                                                {{ $class->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('classes.edit', $class) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('classes.destroy', $class) }}" style="margin: 0;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="sb-empty-state">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                        </svg>
                                        <h5>No classes found</h5>
                                        <p>Get started by creating your first class.</p>
                                        <a href="{{ route('classes.create') }}">+ Add Class</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($schoolClasses->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $schoolClasses->links() }}
        </div>
    @endif
</div>
@endsection
