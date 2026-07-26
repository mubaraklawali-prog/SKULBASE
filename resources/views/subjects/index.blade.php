@extends('layouts.app')

@section('title', 'Subjects - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Subjects</h2>
            <p class="text-muted mb-0">Manage all subjects</p>
        </div>
        <a href="{{ route('subjects.create') }}" class="sb-btn sb-btn-primary">
            + Add Subject
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('subjects.index') }}" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or code..."
                    class="sb-form-input"
                >
                <button type="submit" class="sb-btn sb-btn-dark">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('subjects.index') }}" class="sb-btn sb-btn-secondary">
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
                            <th>Code</th>
                            <th>School</th>
                            <th>Classes</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td><strong>{{ $subject->name }}</strong></td>
                                <td>
                                    @if($subject->code)
                                        <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $subject->code }}</code>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $subject->school->name ?? '—' }}</td>
                                <td>
                                    @if($subject->schoolClasses->count())
                                        @foreach($subject->schoolClasses->take(3) as $class)
                                            <span class="sb-badge sb-badge-class">
                                                {{ $class->name }}{{ $class->section ? ' (' . $class->section . ')' : '' }}
                                            </span>
                                        @endforeach
                                        @if($subject->schoolClasses->count() > 3)
                                            <span class="sb-badge sb-badge-info">
                                                +{{ $subject->schoolClasses->count() - 3 }} more
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->status)
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <form method="POST" action="{{ route('subjects.toggle-status', $subject) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="sb-btn sb-btn-sm {{ $subject->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success' }}">
                                                {{ $subject->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('subjects.show', $subject) }}" class="sb-btn sb-btn-sm sb-btn-secondary d-md-inline-flex d-none">
                                            View
                                        </a>
                                        <a href="{{ route('subjects.edit', $subject) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('subjects.destroy', $subject) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
                                    <p style="margin: 0; font-size: 15px;">No subjects found.</p>
                                    <a href="{{ route('subjects.create') }}" style="color: var(--primary); font-weight: 500; text-decoration: none;">Add your first subject</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($subjects->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $subjects->links() }}
        </div>
    @endif
</div>
@endsection
