@extends('layouts.app')

@section('title', 'Edit Grading Rule - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Grading Rule</h2>
            <p class="text-muted mb-0">Grade: {{ $gradingSystem->grade }} — {{ $gradingSystem->min_score }}% to {{ $gradingSystem->max_score }}%</p>
        </div>
        <a href="{{ route('results.grading-systems.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to List</a>
    </div>

    @if($errors->any())
        <div style="background: #f8d7da; color: #842029; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card stat-card">
        <div class="card-body" style="padding: 32px;">
            <form method="POST" action="{{ route('results.grading-systems.update', $gradingSystem) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">School *</label>
                        <select name="school_id" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $gradingSystem->school_id) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Grade *</label>
                        <input type="text" name="grade" value="{{ old('grade', $gradingSystem->grade) }}" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Minimum Score (%) *</label>
                        <input type="number" name="min_score" value="{{ old('min_score', $gradingSystem->min_score) }}" required min="0" max="100" step="0.01" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Maximum Score (%) *</label>
                        <input type="number" name="max_score" value="{{ old('max_score', $gradingSystem->max_score) }}" required min="0" max="100" step="0.01" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Grade Point</label>
                        <input type="number" name="grade_point" value="{{ old('grade_point', $gradingSystem->grade_point) }}" min="0" step="0.01" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Remark *</label>
                        <input type="text" name="remark" value="{{ old('remark', $gradingSystem->remark) }}" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('results.grading-systems.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Cancel</a>
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">Update Rule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
