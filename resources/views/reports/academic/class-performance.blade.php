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
            <a href="{{ route('reports.export.class-performance.pdf', request()->query()) }}" class="btn" style="background: #dc3545; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.academic.class-performance') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Exam</label>
                    <select name="exam_id" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">Analyze</button>
            </div>
        </div>
    </form>

    @if($performance->isNotEmpty())
        <div class="card stat-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Enrolled</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Tested</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Pass Rate</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Highest</th>
                                <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Lowest</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($performance as $index => $item)
                                <tr>
                                    <td style="padding: 14px 20px; color: #6c757d;">{{ $index + 1 }}</td>
                                    <td style="padding: 14px 20px; font-weight: 500;">{{ $item['class']->name }}</td>
                                    <td style="padding: 14px 20px;">{{ $item['enrolled'] }}</td>
                                    <td style="padding: 14px 20px;">{{ $item['tested'] }}</td>
                                    <td style="padding: 14px 20px; font-weight: 600;">{{ $item['average'] }}%</td>
                                    <td style="padding: 14px 20px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="flex: 1; height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden;">
                                                <div style="height: 100%; width: {{ $item['pass_rate'] }}%; background: {{ $item['pass_rate'] >= 50 ? '#198754' : '#dc3545' }}; border-radius: 3px;"></div>
                                            </div>
                                            <span style="font-size: 12px; font-weight: 600; min-width: 40px;">{{ $item['pass_rate'] }}%</span>
                                        </div>
                                    </td>
                                    <td style="padding: 14px 20px; color: #0f5132;">{{ $item['highest'] }}%</td>
                                    <td style="padding: 14px 20px; color: #842029;">{{ $item['lowest'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">No performance data available. Please select an exam.</p>
            </div>
        </div>
    @endif
</div>
@endsection
