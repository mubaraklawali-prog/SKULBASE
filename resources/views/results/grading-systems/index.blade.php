@extends('layouts.app')

@section('title', 'Grading System - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Grading System</h2>
            <p class="text-muted mb-0">Manage grade ranges and scoring rules</p>
        </div>
        <a href="{{ route('results.grading-systems.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">+ Add Rule</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.grading-systems.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by grade, remark..." style="flex: 1; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px; max-width: 400px;">
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">Search</button>
                @if(request('search'))
                    <a href="{{ route('results.grading-systems.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Clear</a>
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
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Min Score (%)</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Max Score (%)</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Grade</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Grade Point</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Remark</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">School</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gradingSystems as $gradingSystem)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $gradingSystem->min_score }}%</td>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $gradingSystem->max_score }}%</td>
                                <td style="padding: 14px 20px; color: #6c757d; font-weight: 600;">{{ $gradingSystem->grade }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $gradingSystem->grade_point ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $gradingSystem->remark }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $gradingSystem->school->name ?? '—' }}</td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('results.grading-systems.edit', $gradingSystem) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">Edit</a>
                                        <form method="POST" action="{{ route('results.grading-systems.destroy', $gradingSystem) }}" style="margin: 0;" onsubmit="return confirm('Are you sure?');">
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
                                    <p style="margin: 0; font-size: 15px;">No grading rules found.</p>
                                    <a href="{{ route('results.grading-systems.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Create your first rule</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($gradingSystems->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">{{ $gradingSystems->links() }}</div>
    @endif
</div>
@endsection
