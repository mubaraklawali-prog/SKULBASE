@extends('layouts.app')

@section('title', 'Enter Scores - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Enter Scores</h2>
            <p class="text-muted mb-0">Bulk score entry for students</p>
        </div>
        <a href="{{ route('results.scores.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to Dashboard</a>
    </div>

    @if($errors->any())
        <div style="background: #f8d7da; color: #842029; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 24px;">
            <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Select Parameters</h5>
            <form method="GET" action="{{ route('results.scores.create') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Exam *</label>
                        <select name="exam_id" required onchange="this.form.submit()" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- Select Exam --</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }} {{ $exam->term ? '(' . $exam->term . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Class *</label>
                        <select name="school_class_id" required onchange="this.form.submit()" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id', $selectedClass) == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Subject *</label>
                        <select name="subject_id" required onchange="this.form.submit()" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $selectedSubject) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Assessment Type *</label>
                        <select name="assessment_type_id" required onchange="this.form.submit()" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- Select Assessment --</option>
                            @foreach($assessmentTypes as $type)
                                <option value="{{ $type->id }}" {{ old('assessment_type_id', $selectedAssessmentType) == $type->id ? 'selected' : '' }}>{{ $type->name }} ({{ $type->percentage }}%)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedExam && $selectedClass && $selectedSubject && $selectedAssessmentType)
        @if($students->isEmpty())
            <div class="card stat-card">
                <div class="card-body" style="padding: 40px; text-align: center;">
                    <p class="text-muted" style="margin: 0; font-size: 15px;">No active students found in the selected class.</p>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('results.scores.store') }}">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $selectedExam }}">
                <input type="hidden" name="school_class_id" value="{{ $selectedClass }}">
                <input type="hidden" name="subject_id" value="{{ $selectedSubject }}">
                <input type="hidden" name="assessment_type_id" value="{{ $selectedAssessmentType }}">

                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 style="font-weight: 600; color: #1a1a2e; margin: 0;">Enter Scores ({{ $students->count() }} students)</h5>
                            <div class="d-flex gap-2">
                                <button type="button" onclick="fillAllScores(0)" class="btn btn-sm" style="background: #f8d7da; color: #842029; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; border: none; cursor: pointer;">Clear All</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr style="background: #f8f9fa;">
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student Name</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Admission No</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Score (0-100)</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $index + 1 }}</td>
                                            <td style="padding: 12px 16px; font-weight: 500;">{{ $student->full_name }}</td>
                                            <td style="padding: 12px 16px; color: #6c757d;">{{ $student->admission_number }}</td>
                                            <td style="padding: 12px 8px;">
                                                <input type="number" name="scores[{{ $index }}][score]" class="score-input" value="{{ old("scores.{$index}.score", $existingScores[$student->id] ?? '') }}" min="0" max="100" step="0.01" placeholder="0-100" required style="width: 120px; padding: 8px 12px; border-radius: 6px; border: 1px solid #dee2e6; font-size: 14px;">
                                                <input type="hidden" name="scores[{{ $index }}][student_id]" value="{{ $student->id }}">
                                            </td>
                                            <td style="padding: 12px 8px;">
                                                <input type="text" name="scores[{{ $index }}][remarks]" value="{{ old("scores.{$index}.remarks", $existingRemarks[$student->id] ?? '') }}" placeholder="Optional" style="width: 200px; padding: 8px 12px; border-radius: 6px; border: 1px solid #dee2e6; font-size: 14px;">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('results.scores.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Cancel</a>
                            <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">Save Scores</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    @else
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px; text-align: center;">
                <p class="text-muted" style="margin: 0; font-size: 15px;">Please select an exam, class, subject, and assessment type above to load students for score entry.</p>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function fillAllScores(value) {
        document.querySelectorAll('.score-input').forEach(function(input) {
            input.value = value;
        });
    }
</script>
@endpush
@endsection
