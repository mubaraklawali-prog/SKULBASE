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
        <div class="card-body" style="padding: 32px;">
            <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Select Exam & Class</h5>

            <form method="POST" action="{{ route('results.report-cards.bulk-print') }}" id="bulkForm">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-5 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Exam</label>
                        <select name="exam_id" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px; background: #fff;">
                            <option value="">Select Exam</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->name }} @if($exam->term)({{ $exam->term }}@if($exam->session) - {{ $exam->session }}@endif)@endif</option>
                            @endforeach
                        </select>
                        @error('exam_id')
                            <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-5 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Class</label>
                        <select name="school_class_id" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px; background: #fff;">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                            @endforeach
                        </select>
                        @error('school_class_id')
                            <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end gap-2">
                        <button type="submit" formaction="{{ route('results.report-cards.bulk-print') }}" formtarget="_blank" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer; white-space: nowrap;">Print</button>
                        <button type="submit" formaction="{{ route('results.report-cards.bulk-pdf') }}" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; border: none; cursor: pointer; white-space: nowrap;">PDF</button>
                    </div>
                </div>
            </form>

            <hr style="margin: 24px 0; border-color: #e9ecef;">

            <h6 style="font-weight: 600; color: #6c757d; margin-bottom: 12px;">How it works</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #e7f1ff; color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; flex-shrink: 0;">1</div>
                        <div>
                            <p style="font-weight: 600; font-size: 13px; margin: 0; color: #1a1a2e;">Select Exam & Class</p>
                            <p style="font-size: 12px; color: #6c757d; margin: 2px 0 0;">Choose the exam and class for which you want to print report cards.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #e7f1ff; color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; flex-shrink: 0;">2</div>
                        <div>
                            <p style="font-weight: 600; font-size: 13px; margin: 0; color: #1a1a2e;">Choose Output</p>
                            <p style="font-size: 12px; color: #6c757d; margin: 2px 0 0;">Click <strong>Print</strong> for browser print dialog, or <strong>PDF</strong> to download.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #fff3cd; color: #664d03; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; flex-shrink: 0;">!</div>
                        <div>
                            <p style="font-weight: 600; font-size: 13px; margin: 0; color: #1a1a2e;">Only Published</p>
                            <p style="font-size: 12px; color: #6c757d; margin: 2px 0 0;">Only <span style="background: #d1e7dd; color: #0f5132; padding: 1px 8px; border-radius: 10px; font-size: 11px; font-weight: 600;">Published</span> report cards can be printed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
