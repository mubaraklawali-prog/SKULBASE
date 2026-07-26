@extends('layouts.app')

@section('title', 'Teachers - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Teachers</h2>
            <p>Manage all teachers</p>
        </div>
        <a href="{{ route('teachers.create') }}" class="sb-btn sb-btn-primary">+ Add Teacher</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teachers.index') }}" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, email or phone..."
                    class="sb-form-input"
                    style="max-width: 400px;"
                >
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                @if(request('search'))
                    <a href="{{ route('teachers.index') }}" class="sb-btn sb-btn-secondary">Clear</a>
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
                            <th>Teacher</th>
                            <th>School</th>
                            <th>Phone</th>
                            <th>Subjects</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; flex-shrink: 0; background: #e7f1ff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; color: #0d6efd;">
                                            {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 500;">{{ $teacher->full_name }}</div>
                                            <div style="font-size: 12px; color: #6c757d;">{{ $teacher->email ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color: #6c757d;">{{ $teacher->school->name ?? '—' }}</td>
                                <td style="color: #6c757d;">{{ $teacher->phone }}</td>
                                <td>
                                    @if($teacher->subjects->count())
                                        @foreach($teacher->subjects->take(2) as $subject)
                                            <span class="sb-badge-tag">{{ $subject->name }}</span>
                                        @endforeach
                                        @if($teacher->subjects->count() > 2)
                                            <span class="sb-badge-count">+{{ $teacher->subjects->count() - 2 }}</span>
                                        @endif
                                    @else
                                        <span style="color: #6c757d;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($teacher->status)
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <form method="POST" action="{{ route('teachers.toggle-status', $teacher) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="sb-btn sb-btn-sm {{ $teacher->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success' }}">
                                                {{ $teacher->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('teachers.show', $teacher) }}" class="sb-btn sb-btn-sm sb-btn-secondary d-md-inline-flex d-none">View</a>
                                        <a href="{{ route('teachers.edit', $teacher) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
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
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                            <line x1="8" y1="7" x2="16" y2="7"></line>
                                            <line x1="8" y1="11" x2="14" y2="11"></line>
                                        </svg>
                                        <h5>No teachers found</h5>
                                        <p>Get started by adding your first teacher.</p>
                                        <a href="{{ route('teachers.create') }}">+ Add Teacher</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($teachers->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $teachers->links() }}
        </div>
    @endif
</div>
@endsection
