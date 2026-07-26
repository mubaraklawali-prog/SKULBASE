@extends('layouts.app')

@section('title', 'Parents - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Parents</h2>
            <p class="text-muted mb-0">Manage parent accounts and children linking</p>
        </div>
        <a href="{{ route('parents.create') }}" class="sb-btn sb-btn-primary">
            Add Parent
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 16px 24px;">
            <form method="GET" action="{{ route('parents.index') }}">
                <div class="d-flex gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, email, or phone..."
                        class="sb-form-input"
                        style="max-width: 400px;"
                    >
                    <button type="submit" class="sb-btn sb-btn-primary">Search</button>
                    @if(request('search'))
                        <a href="{{ route('parents.index') }}" class="sb-btn sb-btn-secondary">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 0;">
            @if($parents->count())
                <div style="overflow-x: auto;">
                    <table class="sb-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Children</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parents as $parent)
                                <tr>
                                    <td>
                                        <a href="{{ route('parents.show', $parent) }}" style="font-weight: 500; color: #333; text-decoration: none;">
                                            {{ $parent->full_name }}
                                        </a>
                                    </td>
                                    <td>{{ $parent->email ?? '—' }}</td>
                                    <td>{{ $parent->phone ?? '—' }}</td>
                                    <td>
                                        <span class="sb-badge sb-badge-info">
                                            {{ $parent->children->count() }} {{ Str::plural('child', $parent->children->count()) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="sb-badge {{ $parent->status ? 'sb-badge-active' : 'sb-badge-inactive' }}">
                                            {{ $parent->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('parents.edit', $parent) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                            <form method="POST" action="{{ route('parents.destroy', $parent) }}" onsubmit="return confirm('Are you sure you want to delete this parent?');" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding: 16px;">
                    {{ $parents->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 48px 16px; color: #6c757d;">
                    <p style="margin: 0; font-size: 16px;">No parents found.</p>
                    <a href="{{ route('parents.create') }}" class="sb-btn sb-btn-primary mt-3">Add First Parent</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
