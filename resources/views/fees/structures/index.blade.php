@extends('layouts.app')

@section('title', 'Fee Structures - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Fee Structures</h2>
            <p class="text-muted mb-0">Manage fee definitions per class</p>
        </div>
        <a href="{{ route('fees.structures.create') }}" class="sb-btn sb-btn-primary">
            + Add Fee Structure
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('fees.structures.index') }}" class="sb-search-bar">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, term, session..." class="sb-form-input">
                <select name="class_id" class="sb-form-select">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                    @endforeach
                </select>
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                @if(request()->hasAny(['search', 'class_id']))
                    <a href="{{ route('fees.structures.index') }}" class="sb-btn sb-btn-secondary">Clear</a>
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
                            <th>Title</th>
                            <th>Class</th>
                            <th>Amount</th>
                            <th>Term/Session</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feeStructures as $fs)
                            <tr>
                                <td><strong>{{ $fs->title }}</strong></td>
                                <td class="text-muted">{{ $fs->schoolClass->name ?? '—' }}{{ $fs->schoolClass->section ? ' - ' . $fs->schoolClass->section : '' }}</td>
                                <td style="font-weight: 600; color: #0f5132;">₦{{ number_format($fs->amount, 2) }}</td>
                                <td class="text-muted">{{ $fs->term ?? '—' }}{{ $fs->session ? ' / ' . $fs->session : '' }}</td>
                                <td class="text-muted">{{ $fs->due_date ? $fs->due_date->format('M d, Y') : '—' }}</td>
                                <td>
                                    @if($fs->status)
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    @else
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <a href="{{ route('fees.structures.edit', $fs) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('fees.structures.destroy', $fs) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this fee structure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No fee structures found.</p>
                                    <a href="{{ route('fees.structures.create') }}" style="color: var(--primary); font-weight: 500; text-decoration: none;">Create your first fee structure</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($feeStructures->hasPages())
        <div class="mt-3 d-flex justify-content-center">{{ $feeStructures->links() }}</div>
    @endif
</div>
@endsection
