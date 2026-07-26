@extends('layouts.app')

@section('title', 'Compute Results - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Compute Results</h2>
            <p class="text-muted mb-0">Select exam and class to compute results</p>
        </div>
        <a href="{{ route('results.computations.dashboard') }}" class="sb-btn sb-btn-ghost">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body p-3">
            <h5 class="fw-semibold mb-3">Select Exam & Class</h5>
            <form method="POST" action="{{ route('results.computations.run') }}" id="computeForm">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Exam *</label>
                        <select name="exam_id" required class="sb-form-select">
                            <option value="">-- Select Exam --</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }} {{ $exam->term ? '(' . $exam->term . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Class *</label>
                        <select name="school_class_id" required class="sb-form-select">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id', $selectedClass) == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="sb-btn sb-btn-primary w-100">Compute Results</button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('results.computations.compute', ['exam_id' => $selectedExam, 'school_class_id' => $selectedClass]) }}" class="sb-btn sb-btn-ghost w-100 d-block text-center">Refresh View</a>
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
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-excused">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-excused">{{ $reportCards->count() }}</p>
                            <p class="stat-label">Students</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-present">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-present">{{ number_format($classAvg, 1) }}%</p>
                            <p class="stat-label">Class Average</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-late">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-late">@if($reportCards->min('class_position'))@php $pos = $reportCards->min('class_position'); $suffix = match(true) { $pos % 100 >= 11 && $pos % 100 <= 13 => 'th', $pos % 10 === 1 => 'st', $pos % 10 === 2 => 'nd', $pos % 10 === 3 => 'rd', default => 'th' }; @endphp{{ $pos . $suffix }}@else—@endif</p>
                            <p class="stat-label">Top Position</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-present">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-present">{{ $passCount }}/{{ $reportCards->count() }}</p>
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
                    <button type="submit" class="sb-btn sb-btn-primary">Submit All</button>
                </form>
            @endif
            @if($firstStatus === 'submitted')
                <form method="POST" action="{{ route('results.approvals.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $selectedExam }}">
                    <input type="hidden" name="school_class_id" value="{{ $selectedClass }}">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="sb-btn sb-btn-outline-warning">Approve All</button>
                </form>
            @endif
            @if($firstStatus === 'approved')
                <form method="POST" action="{{ route('results.approvals.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $selectedExam }}">
                    <input type="hidden" name="school_class_id" value="{{ $selectedClass }}">
                    <input type="hidden" name="action" value="publish">
                    <button type="submit" class="sb-btn sb-btn-outline-success">Publish All</button>
                </form>
            @endif
            @if(in_array($firstStatus, ['submitted', 'approved', 'published']))
                <a href="{{ route('results.approvals.dashboard', ['exam_id' => $selectedExam, 'school_class_id' => $selectedClass]) }}" class="sb-btn sb-btn-outline-info">Approval Workflow</a>
            @endif
            <a href="{{ route('results.rankings.class', ['exam_id' => $selectedExam, 'school_class_id' => $selectedClass]) }}" class="sb-btn sb-btn-outline-info">View Class Rankings</a>
        </div>

        <div class="card stat-card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">{{ $exam->name ?? '' }} — {{ $class->name ?? '' }} Results</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Student</th>
                                <th>Subjects</th>
                                <th>Passed</th>
                                <th>Failed</th>
                                <th>Average</th>
                                <th>Grade</th>
                                <th>Attendance</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportCards as $card)
                                <tr>
                                    <td class="fw-bold fs-6">@if($card->class_position)@php $pos = $card->class_position; $suffix = match(true) { $pos % 100 >= 11 && $pos % 100 <= 13 => 'th', $pos % 10 === 1 => 'st', $pos % 10 === 2 => 'nd', $pos % 10 === 3 => 'rd', default => 'th' }; @endphp{{ $pos . $suffix }}@else—@endif</td>
                                    <td class="fw-medium">{{ $card->student->full_name ?? '—' }}</td>
                                    <td class="text-muted">{{ $card->total_subjects }}</td>
                                    <td class="text-success fw-semibold">{{ $card->subjects_passed }}</td>
                                    <td class="{{ $card->subjects_failed > 0 ? 'text-danger' : 'text-muted' }} fw-semibold">{{ $card->subjects_failed }}</td>
                                    <td class="fw-semibold">{{ number_format($card->average_score, 1) }}%</td>
                                    <td>
                                        <span class="sb-badge sb-badge-excused">{{ $card->overall_grade ?? '—' }}</span>
                                    </td>
                                    <td class="text-muted">{{ $card->attendance_percentage ? number_format($card->attendance_percentage, 1) . '%' : '—' }}</td>
                                    <td>
                                        @if($card->status === 'published')
                                            <span class="sb-badge sb-badge-present">Published</span>
                                        @elseif($card->status === 'approved')
                                            <span class="sb-badge sb-badge-late">Approved</span>
                                        @elseif($card->status === 'submitted')
                                            <span class="sb-badge sb-badge-excused">Submitted</span>
                                        @elseif($card->status === 'rejected')
                                            <span class="sb-badge sb-badge-absent">Rejected</span>
                                        @else
                                            <span class="sb-badge sb-badge-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('results.computations.show', $card) }}" class="sb-link">View</a>
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
            <div class="card-body sb-empty-state">
                <p>No report cards found. Click "Compute Results" to generate them.</p>
            </div>
        </div>
    @else
        <div class="card stat-card">
            <div class="card-body sb-empty-state">
                <p>Please select an exam and class above to view or compute results.</p>
            </div>
        </div>
    @endif
</div>
@endsection