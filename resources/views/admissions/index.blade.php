@extends('layouts.app')

@section('title', 'Admissions - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Admissions</h2>
            <p class="text-muted mb-0">Manage student admission applications</p>
        </div>
        <a href="{{ route('admissions.form') }}" target="_blank" class="sb-btn sb-btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            Public Form
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admissions.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="sb-form-label">Search</label>
                    <input type="text" name="search" class="sb-form-input" value="{{ request('search') }}" placeholder="App #, name, or phone...">
                </div>
                <div class="col-6 col-md-2">
                    <label class="sb-form-label">Class</label>
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="sb-form-label">Status</label>
                    <select name="status" class="sb-form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-6 col-md-1">
                    <button type="submit" class="sb-btn sb-btn-primary w-100">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'class_id', 'status']))
                    <div class="col-6 col-md-1">
                        <a href="{{ route('admissions.index') }}" class="sb-btn sb-btn-secondary w-100 text-center">Clear</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover sb-table">
                    <thead>
                        <tr>
                            <th>Application #</th>
                            <th>Applicant</th>
                            <th>Parent</th>
                            <th>Class</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admissions as $admission)
                            <tr>
                                <td>
                                    <a href="{{ route('admissions.show', $admission) }}" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 13px;">{{ $admission->application_number }}</a>
                                </td>
                                <td>
                                    <div style="font-weight: 500; color: #333;">{{ $admission->full_name }}</div>
                                    <small class="text-muted">{{ ucfirst($admission->gender) }}</small>
                                </td>
                                <td>
                                    <div style="font-weight: 500; color: #333;">{{ $admission->parent_name }}</div>
                                    <small class="text-muted">{{ $admission->parent_phone }}</small>
                                </td>
                                <td>{{ $admission->schoolClass->name ?? '—' }}</td>
                                <td class="text-muted">{{ $admission->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($admission->status === 'approved')
                                        <span class="sb-badge sb-badge-approved">Approved</span>
                                    @elseif($admission->status === 'rejected')
                                        <span class="sb-badge sb-badge-rejected">Rejected</span>
                                    @else
                                        <span class="sb-badge sb-badge-pending">Pending</span>
                                    @endif
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <div class="table-actions">
                                        <a href="{{ route('admissions.show', $admission) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                        <a href="{{ route('admissions.edit', $admission) }}" class="sb-btn sb-btn-sm sb-btn-secondary">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No admission applications found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($admissions->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $admissions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
