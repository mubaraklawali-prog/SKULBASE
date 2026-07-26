@extends('layouts.app')

@section('title', 'Students - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Students</h2>
            <p>Manage all registered students</p>
        </div>
        <a href="{{ route('students.create') }}" class="sb-btn sb-btn-primary">+ Add Student</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, admission number or email..."
                    class="sb-form-input"
                    style="max-width: 320px;"
                >
                <select
                    name="class_id"
                    class="sb-form-select"
                    style="max-width: 220px;"
                    onchange="this.form.submit()"
                >
                    <option value="">All Classes</option>
                    @foreach($schoolClasses as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                @if(request('search') || request('class_id'))
                    <a href="{{ route('students.index') }}" class="sb-btn sb-btn-secondary">Clear</a>
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
                            <th>Adm. No.</th>
                            <th>Name</th>
                            <th>School</th>
                            <th>Class</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $student->admission_number }}</code>
                                </td>
                                <td style="font-weight: 500;">{{ $student->full_name }}</td>
                                <td style="color: #6c757d;">{{ $student->school->name ?? '—' }}</td>
                                <td>
                                    @if($student->schoolClass)
                                        <span class="sb-badge sb-badge-class">
                                            {{ $student->schoolClass->name }}{{ $student->schoolClass->section ? ' - ' . $student->schoolClass->section : '' }}
                                        </span>
                                    @else
                                        <span style="color: #6c757d;">—</span>
                                    @endif
                                </td>
                                <td style="color: #6c757d; text-transform: capitalize;">{{ $student->gender }}</td>
                                <td>
                                    @if($student->status === 'active')
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <a href="{{ route('students.edit', $student) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('students.destroy', $student) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this student?');">
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
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                        <h5>No students found</h5>
                                        <p>Get started by registering your first student.</p>
                                        <a href="{{ route('students.create') }}">+ Add Student</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($students->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $students->links() }}
        </div>
    @endif
</div>
@endsection
