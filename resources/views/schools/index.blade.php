@extends('layouts.app')

@section('title', 'Schools - Skulbase')

@section('content')
@if(session('success'))
    <div style="background:#d1e7dd; color:#0f5132; padding:12px; border-radius:8px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Schools</h2>
            <p class="text-muted mb-0">Manage all registered schools</p>
        </div>
        <a href="{{ route('schools.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            + Add School
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('schools.index') }}" class="d-flex gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, email or slug..."
                    class="form-control"
                    style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px; max-width: 400px;"
                >
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('schools.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
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
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Name</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Slug</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Email</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Phone</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $school->name }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">
                                    <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $school->slug }}</code>
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $school->email ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $school->phone ?? '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($school->is_active)
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('schools.edit', $school) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('schools.toggle-status', $school) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm" style="background: {{ $school->is_active ? '#fff3cd' : '#d1e7dd' }}; color: {{ $school->is_active ? '#664d03' : '#0f5132' }}; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">
                                                {{ $school->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('schools.destroy', $school) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this school?');">
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
                                    <p style="margin: 0; font-size: 15px;">No schools found.</p>
                                    <a href="{{ route('schools.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Add your first school</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($schools->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $schools->links() }}
        </div>
    @endif
</div>
@endsection
