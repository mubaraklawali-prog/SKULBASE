@extends('layouts.app')

@section('title', 'Fee Structures - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Fee Structures</h2>
            <p class="text-muted mb-0">Manage fee definitions per class</p>
        </div>
        <a href="{{ route('fees.structures.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
            + Add Fee Structure
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('fees.structures.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, term, session..." style="flex: 1; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px; max-width: 400px;">
                <select name="class_id" style="padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">Search</button>
                @if(request()->hasAny(['search', 'class_id']))
                    <a href="{{ route('fees.structures.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Title</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Amount</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Term/Session</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Due Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feeStructures as $fs)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $fs->title }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $fs->schoolClass->name ?? '—' }}{{ $fs->schoolClass->section ? ' - ' . $fs->schoolClass->section : '' }}</td>
                                <td style="padding: 14px 20px; font-weight: 600; color: #0f5132;">₦{{ number_format($fs->amount, 2) }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $fs->term ?? '—' }}{{ $fs->session ? ' / ' . $fs->session : '' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $fs->due_date ? $fs->due_date->format('M d, Y') : '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($fs->status)
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('fees.structures.edit', $fs) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">Edit</a>
                                        <form method="POST" action="{{ route('fees.structures.destroy', $fs) }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this fee structure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #842029; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No fee structures found.</p>
                                    <a href="{{ route('fees.structures.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Create your first fee structure</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($feeStructures->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">{{ $feeStructures->links() }}</div>
    @endif
</div>
@endsection
