@extends('layouts.app')

@section('title', 'Report Cards - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Report Cards</h2>
            <p class="text-muted mb-0">Print or download student report cards</p>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body sb-card-body">
            <h5 class="sb-detail-label mb-4">Select Exam & Class</h5>

            <form method="POST" action="{{ route('results.report-cards.bulk-print') }}" id="bulkForm">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-5 mb-3">
                        <label class="sb-form-label">Exam</label>
                        <select name="exam_id" required class="sb-form-select">
                            <option value="">Select Exam</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->name }} @if($exam->term)({{ $exam->term }}@if($exam->session) - {{ $exam->session }}@endif)@endif</option>
                            @endforeach
                        </select>
                        @error('exam_id')
                            <span class="sb-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="sb-form-label">Class</label>
                        <select name="school_class_id" required class="sb-form-select">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                            @endforeach
                        </select>
                        @error('school_class_id')
                            <span class="sb-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end gap-2">
                        <button type="submit" formaction="{{ route('results.report-cards.bulk-print') }}" formtarget="_blank" class="sb-btn sb-btn-primary">Print</button>
                        <button type="submit" formaction="{{ route('results.report-cards.bulk-pdf') }}" class="sb-btn sb-btn-dark">PDF</button>
                    </div>
                </div>
            </form>

            <hr class="sb-divider">

            <h6 class="sb-detail-label mb-3">How it works</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="sb-step-number">1</div>
                        <div>
                            <p class="fw-semibold mb-0 small">Select Exam & Class</p>
                            <p class="text-muted small mb-0 mt-1">Choose the exam and class for which you want to print report cards.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="sb-step-number">2</div>
                        <div>
                            <p class="fw-semibold mb-0 small">Choose Output</p>
                            <p class="text-muted small mb-0 mt-1">Click <strong>Print</strong> for browser print dialog, or <strong>PDF</strong> to download.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="sb-step-number sb-step-number-warning">!</div>
                        <div>
                            <p class="fw-semibold mb-0 small">Only Published</p>
                            <p class="text-muted small mb-0 mt-1">Only <span class="sb-badge sb-badge-success">Published</span> report cards can be printed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
