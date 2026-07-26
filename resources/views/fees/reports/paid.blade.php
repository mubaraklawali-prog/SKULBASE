@extends('layouts.app')

@section('title', 'Fully Paid Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Fully Paid Report</h2>
            <p class="text-muted mb-0">Students who have paid all fees in full</p>
        </div>
        <a href="{{ route('fees.dashboard') }}" class="sb-btn sb-btn-secondary">Back to Dashboard</a>
    </div>

    <form method="GET" action="{{ route('fees.reports.paid') }}" class="card stat-card mb-4">
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label class="sb-form-label">Filter by Class</label>
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #0f5132;">{{ $paidStudents->count() }}</p>
                    <p class="stat-label">Fully Paid Students</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Adm. No.</th>
                            <th>Class</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paidStudents as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="font-weight: 500;">
                                    <a href="{{ route('fees.student', $student) }}" style="color: #333; text-decoration: none;">{{ $student->full_name }}</a>
                                </td>
                                <td>
                                    <code>{{ $student->admission_number }}</code>
                                </td>
                                <td>{{ $student->schoolClass->name ?? '—' }}</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('fees.student', $student) }}" class="sb-btn sb-btn-outline-success sb-btn-sm">View Profile</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="sb-empty-state">
                                    <p style="margin: 0; font-size: 15px;">No fully paid students found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
