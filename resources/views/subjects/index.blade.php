@extends('layouts.app')

@section('title', 'Subjects - Skulbase')

@section('content')
@if(session('success'))
    <div style="background:#d1e7dd; color:#0f5132; padding:12px; border-radius:8px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Subjects</h2>
            <p class="text-muted mb-0">Manage all subjects</p>
        </div>
        <a href="{{ route('subjects.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            + Add Subject
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('subjects.index') }}" class="d-flex gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or code..."
                    class="form-control"
                    style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px; max-width: 400px;"
                >
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('subjects.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
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
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Code</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">School</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Classes</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $subject->name }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($subject->code)
                                        <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $subject->code }}</code>
                                    @else
                                        <span style="color: #6c757d;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $subject->school->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($subject->schoolClasses->count())
                                        @foreach($subject->schoolClasses->take(3) as $class)
                                            <span style="background: #e7f1ff; color: #0d6efd; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 500; display: inline-block; margin: 1px 2px;">
                                                {{ $class->name }}{{ $class->section ? ' (' . $class->section . ')' : '' }}
                                            </span>
                                        @endforeach
                                        @if($subject->schoolClasses->count() > 3)
                                            <span style="background: #f0f2f5; color: #6c757d; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 500; display: inline-block; margin: 1px 2px;">
                                                +{{ $subject->schoolClasses->count() - 3 }} more
                                            </span>
                                        @endif
                                    @else
                                        <span style="color: #6c757d;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px;">
                                    @if($subject->status)
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <form method="POST" action="{{ route('subjects.toggle-status', $subject) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm" style="background: {{ $subject->status ? '#fff3cd' : '#d1e7dd' }}; color: {{ $subject->status ? '#664d03' : '#0f5132' }}; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">
                                                {{ $subject->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('subjects.show', $subject) }}" class="btn btn-sm" style="background: #f0f2f5; color: #333; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">
                                            View
                                        </a>
                                        <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('subjects.destroy', $subject) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
                                    <p style="margin: 0; font-size: 15px;">No subjects found.</p>
                                    <a href="{{ route('subjects.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Add your first subject</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($subjects->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $subjects->links() }}
        </div>
    @endif
</div>
@endsection
