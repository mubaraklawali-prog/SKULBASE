@extends('layouts.app')

@section('title', 'Student List Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Student List</h2>
            <p class="text-muted mb-0">Complete student roster</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export.students.csv', request()->query()) }}" class="sb-btn sb-btn-primary">Export CSV</a>
            <a href="{{ route('reports.export.students.pdf', request()->query()) }}" class="sb-btn sb-btn-danger">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="sb-btn sb-btn-secondary">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.students.list') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 2;">
                    <label class="sb-form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, adm. no., email..." class="sb-form-input">
                </div>
                <div style="flex: 1;">
                    <label class="sb-form-label">Class</label>
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
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
                            <th>Adm. No.</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Class</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            <tr>
                                <td>{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</td>
                                <td><code>{{ $student->admission_number }}</code></td>
                                <td style="font-weight: 500;">{{ $student->full_name }}</td>
                                <td>{{ ucfirst($student->gender) }}</td>
                                <td>{{ $student->schoolClass->name ?? '—' }}</td>
                                <td>{{ $student->email ?? '—' }}</td>
                                <td>
                                    @if($student->status === 'active')
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="sb-empty-state">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($students->hasPages())
            <div class="card-body" style="border-top: 1px solid #f0f2f5;">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
