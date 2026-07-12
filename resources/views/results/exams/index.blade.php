@extends('layouts.app')

@section('title', 'Exams - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Exams</h2>
            <p class="text-muted mb-0">Manage exams and examination schedules</p>
        </div>
        <a href="{{ route('results.exams.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">+ Add Exam</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.exams.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, term, session..." style="flex: 1; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px; max-width: 400px;">
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500;">Search</button>
                @if(request('search'))
                    <a href="{{ route('results.exams.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Clear</a>
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
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Term</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Session</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Start Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">End Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">School</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $exam->name }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $exam->term ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $exam->session ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $exam->start_date ? $exam->start_date->format('M d, Y') : '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $exam->end_date ? $exam->end_date->format('M d, Y') : '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $exam->school->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($exam->status)
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <form method="POST" action="{{ route('results.exams.toggle-status', $exam) }}" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm" style="background: {{ $exam->status ? '#fff3cd' : '#d1e7dd' }}; color: {{ $exam->status ? '#664d03' : '#0f5132' }}; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">{{ $exam->status ? 'Deactivate' : 'Activate' }}</button>
                                        </form>
                                        <a href="{{ route('results.exams.edit', $exam) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">Edit</a>
                                        <form method="POST" action="{{ route('results.exams.destroy', $exam) }}" style="margin: 0;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #842029; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No exams found.</p>
                                    <a href="{{ route('results.exams.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Create your first exam</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($exams->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">{{ $exams->links() }}</div>
    @endif
</div>
@endsection
