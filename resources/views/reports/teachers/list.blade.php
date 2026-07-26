@extends('layouts.app')

@section('title', 'Teacher List Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Teacher List</h2>
            <p class="text-muted mb-0">Complete teacher roster</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export.teachers.csv', request()->query()) }}" class="sb-btn sb-btn-primary">Export CSV</a>
            <a href="{{ route('reports.export.teachers.pdf', request()->query()) }}" class="sb-btn sb-btn-danger">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="sb-btn sb-btn-secondary">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.teachers.list') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 2;">
                    <label class="sb-form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, phone..." class="sb-form-input">
                </div>
                <div style="flex: 1;">
                    <label class="sb-form-label">Status</label>
                    <select name="status" class="sb-form-select">
                        <option value="active" {{ request('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
            </div>
        </div>
    </form>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subjects</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $index => $teacher)
                            <tr>
                                <td>{{ ($teachers->currentPage() - 1) * $teachers->perPage() + $index + 1 }}</td>
                                <td style="font-weight: 500;">{{ $teacher->full_name }}</td>
                                <td>{{ ucfirst($teacher->gender) }}</td>
                                <td>{{ $teacher->email ?? '—' }}</td>
                                <td>{{ $teacher->phone }}</td>
                                <td>
                                    @forelse($teacher->subjects as $subject)
                                        <span class="sb-badge sb-badge-info">{{ $subject->name }}</span>
                                    @empty
                                        <span style="color: #adb5bd;">—</span>
                                    @endforelse
                                </td>
                                <td>
                                    @if($teacher->status)
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="sb-empty-state">No teachers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($teachers->hasPages())
            <div class="card-body" style="border-top: 1px solid #f0f2f5;">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
