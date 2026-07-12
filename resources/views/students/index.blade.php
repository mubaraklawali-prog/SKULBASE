@extends('layouts.app')

@section('title', 'Students - Skulbase')

@section('content')
@if(session('success'))
    <div style="background:#d1e7dd; color:#0f5132; padding:12px; border-radius:8px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Students</h2>
            <p class="text-muted mb-0">Manage all registered students</p>
        </div>
        <a href="{{ route('students.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            + Add Student
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" class="d-flex gap-2 align-items-center">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, admission number or email..."
                    class="form-control"
                    style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px; max-width: 320px;"
                >
                <select
                    name="class_id"
                    class="form-control"
                    style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px; max-width: 220px;"
                    onchange="this.form.submit()"
                >
                    <option value="">All Classes</option>
                    @foreach($schoolClasses as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">
                    Search
                </button>
                @if(request('search') || request('class_id'))
                    <a href="{{ route('students.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="margin-bottom: 0;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Adm. No.</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Name</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">School</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Gender</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td style="padding: 14px 20px;">
                                    <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $student->admission_number }}</code>
                                </td>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $student->full_name }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $student->school->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($student->schoolClass)
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                                            {{ $student->schoolClass->name }}{{ $student->schoolClass->section ? ' - ' . $student->schoolClass->section : '' }}
                                        </span>
                                    @else
                                        <span style="color: #6c757d;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d; text-transform: capitalize;">{{ $student->gender }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($student->status === 'active')
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('students.destroy', $student) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #842029; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No students found.</p>
                                    <a href="{{ route('students.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Add your first student</a>
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
