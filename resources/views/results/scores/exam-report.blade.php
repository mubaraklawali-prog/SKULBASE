@extends('layouts.app')

@section('title', 'Exam Score Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Exam Score Report</h2>
            <p class="text-muted mb-0">View scores by exam</p>
        </div>
        <a href="{{ route('results.scores.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.reports.exam') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Exam *</label>
                    <select name="exam_id" required style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }} {{ $exam->term ? '(' . $exam->term . ')' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Class (Optional)</label>
                    <select name="school_class_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('school_class_id', $selectedClass) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Subject (Optional)</label>
                    <select name="subject_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $selectedSubject) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 8px 16px; font-weight: 500; font-size: 13px;">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    @if($report)
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #0d6efd;">{{ number_format($scores->count()) }}</p>
                            <p class="stat-label">Total Entries</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #0f5132;">{{ $scores->count() > 0 ? number_format($scores->avg('score'), 1) : '0' }}%</p>
                            <p class="stat-label">Overall Average</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #664d03;">{{ $scores->count() > 0 ? $scores->max('score') : '0' }}%</p>
                            <p class="stat-label">Highest Score</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #f8d7da; color: #842029;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #842029;">{{ $scores->count() > 0 ? $scores->min('score') : '0' }}%</p>
                            <p class="stat-label">Lowest Score</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($classSummary->isNotEmpty())
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Class Summary</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Entries</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Highest</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Lowest</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classSummary as $item)
                                    <tr>
                                        <td style="padding: 12px 16px; font-weight: 500;">{{ $item['class']->name ?? '—' }}{{ $item['class']->section ? ' - ' . $item['class']->section : '' }}</td>
                                        <td style="padding: 12px 16px; color: #6c757d;">{{ $item['total_entries'] }}</td>
                                        <td style="padding: 12px 16px; font-weight: 600;">{{ $item['avg_score'] }}%</td>
                                        <td style="padding: 12px 16px; color: #0f5132; font-weight: 600;">{{ $item['highest'] }}%</td>
                                        <td style="padding: 12px 16px; color: #842029; font-weight: 600;">{{ $item['lowest'] }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($subjectSummary->isNotEmpty())
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Subject Summary</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Subject</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Entries</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Highest</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Lowest</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjectSummary as $item)
                                    <tr>
                                        <td style="padding: 12px 16px; font-weight: 500;">{{ $item['subject']->name ?? '—' }}</td>
                                        <td style="padding: 12px 16px; color: #6c757d;">{{ $item['total_entries'] }}</td>
                                        <td style="padding: 12px 16px; font-weight: 600;">{{ $item['avg_score'] }}%</td>
                                        <td style="padding: 12px 16px; color: #0f5132; font-weight: 600;">{{ $item['highest'] }}%</td>
                                        <td style="padding: 12px 16px; color: #842029; font-weight: 600;">{{ $item['lowest'] }}%</td>
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
            <div class="card-body" style="padding: 60px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">Please select an exam above to generate the report.</p>
            </div>
        </div>
    @endif
</div>
@endsection
