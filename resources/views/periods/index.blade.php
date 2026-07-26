@extends('layouts.app')

@section('title', 'Periods - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Periods</h2>
            <p class="text-muted mb-0">Manage school timetable periods</p>
        </div>
        <a href="{{ route('periods.create') }}" class="sb-btn sb-btn-primary">
            + Add Period
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('periods.index') }}" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or type..."
                    class="sb-form-input"
                >
                <select
                    name="school_id"
                    class="sb-form-select"
                >
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="sb-btn sb-btn-dark">
                    Search
                </button>
                @if(request('search') || request('school_id'))
                    <a href="{{ route('periods.index') }}" class="sb-btn sb-btn-secondary">
                        Clear
                    </a>
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
                            <th>Order</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Time</th>
                            <th>Duration</th>
                            <th>School</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periods as $period)
                            <tr>
                                <td style="font-weight: 500;">{{ $period->sort_order }}</td>
                                <td style="font-weight: 500;">{{ $period->name }}</td>
                                <td>
                                    @php
                                        $typeColors = [
                                            'academic' => ['bg' => '#e7f1ff', 'text' => '#0d6efd'],
                                            'break' => ['bg' => '#fff3cd', 'text' => '#664d03'],
                                            'lunch' => ['bg' => '#d1e7dd', 'text' => '#0f5132'],
                                            'assembly' => ['bg' => '#f0d9ff', 'text' => '#6f42c1'],
                                            'other' => ['bg' => '#f0f2f5', 'text' => '#6c757d'],
                                        ];
                                        $colors = $typeColors[$period->type] ?? $typeColors['other'];
                                    @endphp
                                    <span class="sb-badge" style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                        {{ ucfirst($period->type) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $period->start_time->format('h:i A') }} - {{ $period->end_time->format('h:i A') }}
                                </td>
                                <td style="color: #6c757d;">
                                    {{ $period->duration_minutes }} min
                                </td>
                                <td style="color: #6c757d;">{{ $period->school->name ?? '—' }}</td>
                                <td>
                                    @if($period->status)
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <form method="POST" action="{{ route('periods.toggle-status', $period) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="sb-btn sb-btn-sm {{ $period->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success' }}">
                                                {{ $period->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('periods.show', $period) }}" class="sb-btn sb-btn-sm sb-btn-secondary d-md-inline-flex d-none">
                                            View
                                        </a>
                                        <a href="{{ route('periods.edit', $period) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('periods.destroy', $period) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this period?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="sb-empty-state">
                                        <p style="margin: 0; font-size: 15px;">No periods found.</p>
                                        <a href="{{ route('periods.create') }}">Add your first period</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($periods->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $periods->links() }}
        </div>
    @endif
</div>
@endsection
