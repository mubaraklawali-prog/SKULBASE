@extends('layouts.app')

@section('title', 'Results Summary - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Results Summary</h2>
            <p class="text-muted mb-0">Exam performance overview</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export.results.csv', request()->query()) }}" class="sb-btn sb-btn-primary">Export CSV</a>
            <a href="{{ route('reports.export.results.pdf', request()->query()) }}" class="sb-btn sb-btn-danger">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="sb-btn sb-btn-secondary">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.academic.results') }}" class="card stat-card mb-4">
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
                <div style="flex: 1;">
                    <label class="sb-form-label">Class (Optional)</label>
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">Analyze</button>
            </div>
        </div>
    </form>

    @if($summary)
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center">
                        <p class="stat-number" style="font-size: 24px; color: #0d6efd;">{{ $summary['total_students'] }}</p>
                        <p class="stat-label">Students Tested</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center">
                        <p class="stat-number" style="font-size: 24px; color: #0f5132;">{{ $summary['average_score'] }}%</p>
                        <p class="stat-label">Average Score</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center">
                        <p class="stat-number" style="font-size: 24px; color: #0f5132;">{{ $summary['pass_rate'] }}%</p>
                        <p class="stat-label">Pass Rate</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body text-center">
                        <p class="stat-number" style="font-size: 24px; color: #664d03;">{{ $summary['highest_score'] }}%</p>
                        <p class="stat-label">Highest Score</p>
                    </div>
                </div>
            </div>
        </div>

        @if($summary['subject_averages']->isNotEmpty())
            <div class="card stat-card">
                <div class="card-body">
                    <h6 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Subject Averages</h6>
                    <div class="table-responsive">
                        <table class="sb-table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Records</th>
                                    <th>Average</th>
                                    <th>Highest</th>
                                    <th>Lowest</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary['subject_averages'] as $item)
                                    <tr>
                                        <td style="font-weight: 500;">{{ $item['subject']->name ?? '—' }}</td>
                                        <td>{{ $item['count'] }}</td>
                                        <td style="font-weight: 600;">{{ $item['average'] }}%</td>
                                        <td style="color: #0f5132;">{{ $item['highest'] }}%</td>
                                        <td style="color: #842029;">{{ $item['lowest'] }}%</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="flex: 1; height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden;">
                                                    <div style="height: 100%; width: {{ $item['average'] }}%; background: {{ $item['average'] >= 50 ? '#198754' : '#dc3545' }}; border-radius: 3px;"></div>
                                                </div>
                                                <span style="font-size: 12px; font-weight: 600; min-width: 35px;">{{ $item['average'] }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p style="margin: 0; font-size: 15px;">No exam data available. Please select an exam to view results.</p>
            </div>
        </div>
    @endif
</div>
@endsection
