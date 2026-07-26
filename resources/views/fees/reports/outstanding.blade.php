@extends('layouts.app')

@section('title', 'Outstanding Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Outstanding Report</h2>
            <p class="text-muted mb-0">Students with unpaid balances</p>
        </div>
        <a href="{{ route('fees.dashboard') }}" class="sb-btn sb-btn-secondary">Back to Dashboard</a>
    </div>

    <form method="GET" action="{{ route('fees.reports.outstanding') }}" class="card stat-card mb-4">
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
                    <p class="stat-number" style="font-size: 24px; color: #842029;">{{ $outstandingStudents->count() }}</p>
                    <p class="stat-label">Students with Balance</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">₦{{ number_format($totalOutstanding, 2) }}</p>
                    <p class="stat-label">Total Outstanding</p>
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
                            <th>Class</th>
                            <th>Total Fees</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($outstandingStudents as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="font-weight: 500;">
                                    <a href="{{ route('fees.student', $item['student']) }}" style="color: #333; text-decoration: none;">{{ $item['student']->full_name }}</a>
                                </td>
                                <td>{{ $item['student']->schoolClass->name ?? '—' }}</td>
                                <td>₦{{ number_format($item['total_fees'], 2) }}</td>
                                <td style="color: #0f5132;">₦{{ number_format($item['total_paid'], 2) }}</td>
                                <td style="font-weight: 600; color: #842029;">₦{{ number_format($item['balance'], 2) }}</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('fees.student', $item['student']) }}" class="sb-btn sb-btn-outline-primary sb-btn-sm">View Profile</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="sb-empty-state">
                                    <p style="margin: 0; font-size: 15px;">No outstanding balances found. All students are fully paid!</p>
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
