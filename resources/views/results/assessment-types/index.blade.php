@extends('layouts.app')

@section('title', 'Assessment Types - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Assessment Types</h2>
            <p class="text-muted mb-0">Manage assessment categories and their weights</p>
        </div>
        <a href="{{ route('results.assessment-types.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">+ Add Assessment Type</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.assessment-types.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="flex: 1; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px; max-width: 400px;">
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">Search</button>
                @if(request('search'))
                    <a href="{{ route('results.assessment-types.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Clear</a>
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
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Name</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Percentage</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">School</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assessmentTypes as $type)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $type->name }}</td>
                                <td style="padding: 14px 20px;">
                                    <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ number_format($type->percentage, 1) }}%</span>
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $type->school->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($type->status)
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <form method="POST" action="{{ route('results.assessment-types.toggle-status', $type) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm" style="background: {{ $type->status ? '#fff3cd' : '#d1e7dd' }}; color: {{ $type->status ? '#664d03' : '#0f5132' }}; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">{{ $type->status ? 'Deactivate' : 'Activate' }}</button>
                                        </form>
                                        <a href="{{ route('results.assessment-types.edit', $type) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">Edit</a>
                                        <form method="POST" action="{{ route('results.assessment-types.destroy', $type) }}" style="margin: 0;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #842029; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No assessment types found.</p>
                                    <a href="{{ route('results.assessment-types.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Create your first assessment type</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($assessmentTypes->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">{{ $assessmentTypes->links() }}</div>
    @endif
</div>
@endsection
