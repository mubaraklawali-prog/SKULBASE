@extends('layouts.app')

@section('title', 'Enter Scores - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Enter Scores</h2>
            <p class="text-muted mb-0">Bulk score entry for students</p>
        </div>
        <a href="{{ route('results.scores.dashboard') }}" class="sb-btn sb-btn-outline-secondary">Back to Dashboard</a>
    </div>

    @if($errors->any())
        <div class="sb-alert sb-alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="sb-card mb-4">
        <div class="card-body p-3">
            <h5 class="fw-semibold mb-3">Select Parameters</h5>
            <form method="GET" action="{{ route('results.scores.create') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Exam *</label>
                        <select name="exam_id" required onchange="this.form.submit()" class="sb-form-select">
                            <option value="">-- Select Exam --</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExam) == $exam->id ? 'selected' : '' }}>{{ $exam->name }} {{ $exam->term ? '(' . $exam->term . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Class *</label>
                        <select name="school_class_id" required onchange="this.form.submit()" class="sb-form-select">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id', $selectedClass) == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Subject *</label>
                        <select name="subject_id" required onchange="this.form.submit()" class="sb-form-select">
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $selectedSubject) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Assessment Type *</label>
                        <select name="assessment_type_id" required onchange="this.form.submit()" class="sb-form-select">
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
            <div class="sb-card">
                <div class="card-body sb-empty-state">
                    <p>No active students found in the selected class.</p>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('results.scores.store') }}">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $selectedExam }}">
                <input type="hidden" name="school_class_id" value="{{ $selectedClass }}">
                <input type="hidden" name="subject_id" value="{{ $selectedSubject }}">
                <input type="hidden" name="assessment_type_id" value="{{ $selectedAssessmentType }}">

                <div class="sb-card sb-table-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold mb-0">Enter Scores ({{ $students->count() }} students)</h5>
                            <button type="button" onclick="fillAllScores(0)" class="sb-btn sb-btn-sm sb-btn-outline-danger">Clear All</button>
                        </div>
                        <div class="table-responsive">
                            <table class="sb-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Admission No</th>
                                        <th>Score (0-100)</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr>
                                            <td class="text-muted">{{ $index + 1 }}</td>
                                            <td class="fw-medium">{{ $student->full_name }}</td>
                                            <td class="text-muted">{{ $student->admission_number }}</td>
                                            <td>
                                                <input type="number" name="scores[{{ $index }}][score]" class="score-input sb-form-input" style="width: 120px;" value="{{ old("scores.{$index}.score", $existingScores[$student->id] ?? '') }}" min="0" max="100" step="0.01" placeholder="0-100" required>
                                                <input type="hidden" name="scores[{{ $index }}][student_id]" value="{{ $student->id }}">
                                            </td>
                                            <td>
                                                <input type="text" name="scores[{{ $index }}][remarks]" class="sb-form-input" style="width: 200px;" value="{{ old("scores.{$index}.remarks", $existingRemarks[$student->id] ?? '') }}" placeholder="Optional">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('results.scores.dashboard') }}" class="sb-btn sb-btn-outline-secondary">Cancel</a>
                            <button type="submit" class="sb-btn sb-btn-primary">Save Scores</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    @else
        <div class="sb-card">
            <div class="card-body sb-empty-state">
                <p>Please select an exam, class, subject, and assessment type above to load students for score entry.</p>
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