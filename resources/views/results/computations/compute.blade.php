@extends('layouts.app')

@section('title', 'Compute Results - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Compute Results</h2>
            <p class="text-muted mb-0">Select exam and class to compute results</p>
        </div>
        <a href="{{ route('results.computations.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 24px;">
            <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Select Exam & Class</h5>
            <form method="POST" action="{{ route('results.computations.run') }}" id="computeForm">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Exam *</label>
                        <select name="exam_id" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- Select Exam --</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }} {{ $exam->term ? '(' . $exam->term . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Class *</label>
                        <select name="school_class_id" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id', $selectedClass) == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer; width: 100%;">Compute Results</button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('results.computations.compute', ['exam_id' => $selectedExam, 'school_class_id' => $selectedClass]) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none; width: 100%; display: block; text-align: center;">Refresh View</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedExam && $selectedClass && $reportCards->isNotEmpty())
        @php
            $exam = \App\Models\Exam::find($selectedExam);
            $class = \App\Models\SchoolClass::find($selectedClass);
            $classAvg = $reportCards->avg('average_score');
            $passCount = $reportCards->where('average_score', '>=', 50)->count();
            $topStudent = $reportCards->first();
        @endphp

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #0d6efd;">{{ $reportCards->count() }}</p>
                            <p class="stat-label">Students</p>
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
                            <p class="stat-number" style="color: #0f5132;">{{ number_format($classAvg, 1) }}%</p>
                            <p class="stat-label">Class Average</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #664d03;">{{ $reportCards->min('class_position') ? $this->ordinal($reportCards->min('class_position')) : '—' }}</p>
                            <p class="stat-label">Top Position</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number" style="color: #0f5132;">{{ $passCount }}/{{ $reportCards->count() }}</p>
                            <p class="stat-label">Pass Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-4">
            @php $firstStatus = $reportCards->first()->status ?? 'draft'; @endphp
            @if($firstStatus === 'draft')
                <form method="POST" action="{{ route('results.approvals.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $selectedExam }}">
                    <input type="hidden" name="school_class_id" value="{{ $selectedClass }}">
                    <input type="hidden" name="action" value="submit">
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer;">Submit All</button>
                </form>
            @endif
            @if($firstStatus === 'submitted')
                <form method="POST" action="{{ route('results.approvals.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $selectedExam }}">
                    <input type="hidden" name="school_class_id" value="{{ $selectedClass }}">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn" style="background: #fff3cd; color: #664d03; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer;">Approve All</button>
                </form>
            @endif
            @if($firstStatus === 'approved')
                <form method="POST" action="{{ route('results.approvals.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $selectedExam }}">
                    <input type="hidden" name="school_class_id" value="{{ $selectedClass }}">
                    <input type="hidden" name="action" value="publish">
                    <button type="submit" class="btn" style="background: #d1e7dd; color: #0f5132; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer;">Publish All</button>
                </form>
            @endif
            @if(in_array($firstStatus, ['submitted', 'approved', 'published']))
                <a href="{{ route('results.approvals.dashboard', ['exam_id' => $selectedExam, 'school_class_id' => $selectedClass]) }}" class="btn" style="background: #e7f1ff; color: #0d6efd; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Approval Workflow</a>
            @endif
            <a href="{{ route('results.rankings.class', ['exam_id' => $selectedExam, 'school_class_id' => $selectedClass]) }}" class="btn" style="background: #e7f1ff; color: #0d6efd; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">View Class Rankings</a>
        </div>

        <div class="card stat-card">
            <div class="card-body">
                <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">{{ $exam->name ?? '' }} — {{ $class->name ?? '' }} Results</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Position</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Subjects</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Passed</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Failed</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Grade</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Attendance</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportCards as $card)
                                <tr>
                                    <td style="padding: 12px 16px; font-weight: 600; font-size: 16px;">{{ $card->class_position ? $this->ordinal($card->class_position) : '—' }}</td>
                                    <td style="padding: 12px 16px; font-weight: 500;">{{ $card->student->full_name ?? '—' }}</td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $card->total_subjects }}</td>
                                    <td style="padding: 12px 16px; color: #0f5132; font-weight: 600;">{{ $card->subjects_passed }}</td>
                                    <td style="padding: 12px 16px; color: {{ $card->subjects_failed > 0 ? '#842029' : '#6c757d' }}; font-weight: 600;">{{ $card->subjects_failed }}</td>
                                    <td style="padding: 12px 16px; font-weight: 600;">{{ number_format($card->average_score, 1) }}%</td>
                                    <td style="padding: 12px 16px;">
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $card->overall_grade ?? '—' }}</span>
                                    </td>
                                    <td style="padding: 12px 16px; color: #6c757d;">{{ $card->attendance_percentage ? number_format($card->attendance_percentage, 1) . '%' : '—' }}</td>
                                    <td style="padding: 12px 16px;">
                                        @if($card->status === 'published')
                                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Published</span>
                                        @elseif($card->status === 'approved')
                                            <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Approved</span>
                                        @elseif($card->status === 'submitted')
                                            <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Submitted</span>
                                        @elseif($card->status === 'rejected')
                                            <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Rejected</span>
                                        @else
                                            <span style="background: #f0f2f5; color: #6c757d; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Draft</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 16px; text-align: right;">
                                        <a href="{{ route('results.computations.show', $card) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($selectedExam && $selectedClass)
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">No report cards found. Click "Compute Results" to generate them.</p>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">Please select an exam and class above to view or compute results.</p>
            </div>
        </div>
    @endif
</div>
@endsection
