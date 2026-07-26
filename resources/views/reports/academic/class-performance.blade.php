@extends('layouts.app')

@section('title', 'Class Performance - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Class Performance</h2>
            <p class="text-muted mb-0">Comparative class analysis</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export.class-performance.pdf', request()->query()) }}" class="sb-btn sb-btn-danger">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="sb-btn sb-btn-secondary">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.academic.class-performance') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label class="sb-form-label">Exam</label>
                    <select name="exam_id" class="sb-form-select">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">Analyze</button>
            </div>
        </div>
    </form>

    @if($performance->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="sb-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Class</th>
                                <th>Enrolled</th>
                                <th>Tested</th>
                                <th>Average</th>
                                <th>Pass Rate</th>
                                <th>Highest</th>
                                <th>Lowest</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($performance as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="font-weight: 500;">{{ $item['class']->name }}</td>
                                    <td>{{ $item['enrolled'] }}</td>
                                    <td>{{ $item['tested'] }}</td>
                                    <td style="font-weight: 600;">{{ $item['average'] }}%</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="flex: 1; height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden;">
                                                <div style="height: 100%; width: {{ $item['pass_rate'] }}%; background: {{ $item['pass_rate'] >= 50 ? '#198754' : '#dc3545' }}; border-radius: 3px;"></div>
                                            </div>
                                            <span style="font-size: 12px; font-weight: 600; min-width: 40px;">{{ $item['pass_rate'] }}%</span>
                                        </div>
                                    </td>
                                    <td style="color: #0f5132;">{{ $item['highest'] }}%</td>
                                    <td style="color: #842029;">{{ $item['lowest'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p style="margin: 0; font-size: 15px;">No performance data available. Please select an exam.</p>
            </div>
        </div>
    @endif
</div>
@endsection
