@extends('layouts.app')

@section('title', 'Pending Schools - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Pending Schools</h2>
            <p class="mb-0">Review and approve school registration requests</p>
        </div>
    </div>

    @if (session('success'))
        <div class="sb-flash sb-flash-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="sb-flash sb-flash-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="card stat-card">
        <div class="card-body">
            <div class="sb-search-bar mb-3">
                <form method="GET" action="{{ route('pending-schools.index') }}" class="d-flex gap-2 flex-wrap" style="width: 100%;">
                    <input type="text" name="search" class="sb-form-input" style="max-width: 300px;"
                           placeholder="Search by name or email..." value="{{ request('search') }}">
                    <button type="submit" class="sb-btn sb-btn-primary sb-btn-sm">Search</button>
                    @if (request('search'))
                        <a href="{{ route('pending-schools.index') }}" class="sb-btn sb-btn-secondary sb-btn-sm">Clear</a>
                    @endif
                </form>
            </div>

            @if ($schools->count() > 0)
                <div class="table-responsive">
                    <table class="table sb-table mb-0">
                        <thead>
                            <tr>
                                <th>School Name</th>
                                <th>Admin</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registered</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schools as $school)
                                <tr>
                                    <td>
                                        <strong>{{ $school->name }}</strong>
                                        @if ($school->school_type)
                                            <br><small class="text-muted">{{ $school->school_type }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $school->users()->where('role', 'school_admin')->first()->name ?? '-' }}</td>
                                    <td>{{ $school->email ?? '-' }}</td>
                                    <td>{{ $school->phone ?? '-' }}</td>
                                    <td>{{ $school->registered_at ? $school->registered_at->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <span class="sb-badge sb-badge-pending">Pending</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="table-actions">
                                            <a href="{{ route('pending-schools.show', $school) }}"
                                               class="sb-btn sb-btn-outline-primary sb-btn-sm" title="View">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $schools->links() }}
                </div>
            @else
                <div class="sb-empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                    <h5>No Pending Schools</h5>
                    <p>There are no school registrations waiting for approval.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
