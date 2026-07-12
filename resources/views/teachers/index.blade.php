@extends('layouts.app')

@section('title', 'Teachers - Skulbase')

@section('content')
@if(session('success'))
    <div style="background:#d1e7dd; color:#0f5132; padding:12px; border-radius:8px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Teachers</h2>
            <p class="text-muted mb-0">Manage all teachers</p>
        </div>
        <a href="{{ route('teachers.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            + Add Teacher
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teachers.index') }}" class="d-flex gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, email or phone..."
                    class="form-control"
                    style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px; max-width: 400px;"
                >
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('teachers.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
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
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Teacher</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">School</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Phone</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Subjects</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td style="padding: 14px 20px;">
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
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $teacher->school->name ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $teacher->phone }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($teacher->subjects->count())
                                        @foreach($teacher->subjects->take(2) as $subject)
                                            <span style="background: #e7f1ff; color: #0d6efd; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 500; display: inline-block; margin: 1px 2px;">
                                                {{ $subject->name }}
                                            </span>
                                        @endforeach
                                        @if($teacher->subjects->count() > 2)
                                            <span style="background: #f0f2f5; color: #6c757d; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 500; display: inline-block; margin: 1px 2px;">
                                                +{{ $teacher->subjects->count() - 2 }}
                                            </span>
                                        @endif
                                    @else
                                        <span style="color: #6c757d;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px;">
                                    @if($teacher->status)
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <form method="POST" action="{{ route('teachers.toggle-status', $teacher) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm" style="background: {{ $teacher->status ? '#fff3cd' : '#d1e7dd' }}; color: {{ $teacher->status ? '#664d03' : '#0f5132' }}; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">
                                                {{ $teacher->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm" style="background: #f0f2f5; color: #333; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">
                                            View
                                        </a>
                                        <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
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
                                <td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No teachers found.</p>
                                    <a href="{{ route('teachers.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Add your first teacher</a>
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
