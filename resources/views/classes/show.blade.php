@extends('layouts.app')

@section('title', $schoolClass->name . ' - Skulbase')

@section('content')
    <div class="welcome-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>{{ $schoolClass->name }}{{ $schoolClass->section ? ' - ' . $schoolClass->section : '' }}</h2>
                <p class="text-muted mb-0">
                    {{ optional($schoolClass->school)->name ?? 'No School' }}
                    &middot;
                    {{ $schoolClass->students_count }}
                    {{ \Illuminate\Support\Str::plural('student', $schoolClass->students_count) }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('classes.edit', $schoolClass) }}" class="sb-btn sb-btn-outline-primary">
                    Edit Class
                </a>
                <a href="{{ route('classes.index') }}" class="sb-btn sb-btn-secondary">
                    Back to List
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body" style="padding: 24px;">
                        <label class="sb-form-label">Class Name</label>
                        <p style="margin: 0; font-size: 15px; font-weight: 500; color: #333;">{{ $schoolClass->name }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body" style="padding: 24px;">
                        <label class="sb-form-label">Section</label>
                        <p style="margin: 0; font-size: 15px; color: #333;">{{ $schoolClass->section ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body" style="padding: 24px;">
                        <label class="sb-form-label">Status</label>
                        <p style="margin: 0;">
                            @if ($schoolClass->status)
                                <span class="sb-badge sb-badge-active">Active</span>
                            @else
                                <span class="sb-badge sb-badge-inactive">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if ($schoolClass->description)
            <div class="card stat-card mb-4">
                <div class="card-body" style="padding: 24px;">
                    <label class="sb-form-label">Description</label>
                    <p style="margin: 0; font-size: 15px; color: #333;">{{ $schoolClass->description }}</p>
                </div>
            </div>
        @endif

        <div class="card stat-card">
            <div class="card-body p-0">
                <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
                    <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">Students in this Class</h5>
                </div>
                <div class="table-responsive">
                    <table class="sb-table table table-hover mb-0">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th>Adm. No.</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td>
                                        <code>{{ $student->admission_number }}</code>
                                    </td>
                                    <td style="font-weight: 500;">{{ $student->full_name }}</td>
                                    <td style="text-transform: capitalize;">
                                        {{ $student->gender }}</td>
                                    <td>{{ $student->email ?? '—' }}</td>
                                    <td>
                                        @if ($student->status === 'active')
                                            <span class="sb-badge sb-badge-active">Active</span>
                                        @else
                                            <span class="sb-badge sb-badge-inactive">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                        <p style="margin: 0; font-size: 15px;">No students assigned to this class yet.</p>
                                        <a href="{{ route('students.create') }}" class="sb-btn sb-btn-sm sb-btn-outline-primary mt-2">Add a student</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($students->hasPages())
            <div class="mt-3" style="display: flex; justify-content: center;">
                {{ $students->links() }}
            </div>
        @endif
    </div>
@endsection
