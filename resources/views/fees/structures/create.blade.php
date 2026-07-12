@extends('layouts.app')

@section('title', 'Create Fee Structure - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Create Fee Structure</h2>
            <p class="text-muted mb-0">Define a new fee for a class</p>
        </div>
        <a href="{{ route('fees.structures.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to List</a>
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
            <form method="POST" action="{{ route('fees.structures.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Tuition Fee, Exam Fee" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Class *</label>
                        <select name="school_class_id" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Amount (₦) *</label>
                        <input type="number" name="amount" value="{{ old('amount') }}" required min="0.01" step="0.01" placeholder="0.00" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Term</label>
                        <input type="text" name="term" value="{{ old('term') }}" placeholder="e.g. First Term" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Session</label>
                        <input type="text" name="session" value="{{ old('session') }}" placeholder="e.g. 2025/2026" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                    </div>
                    <div class="col-12 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Description</label>
                        <textarea name="description" rows="3" placeholder="Optional description" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('fees.structures.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Cancel</a>
                    <button type="submit" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">Create Fee Structure</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
