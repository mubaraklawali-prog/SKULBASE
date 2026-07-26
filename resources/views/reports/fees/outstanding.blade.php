@extends('layouts.app')

@section('title', 'Outstanding Fees Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Outstanding Fees</h2>
            <p class="text-muted mb-0">Students with unpaid balances</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export.outstanding.csv', request()->query()) }}" class="sb-btn sb-btn-primary">Export CSV</a>
            <a href="{{ route('reports.export.outstanding.pdf', request()->query()) }}" class="sb-btn sb-btn-danger">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="sb-btn sb-btn-secondary">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.fees.outstanding') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label class="sb-form-label">Filter by Class</label>
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">{{ number_format($count) }}</p>
                    <p class="stat-label">Students with Balance</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">₦{{ number_format($total_outstanding, 2) }}</p>
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
                            <th>Adm. No.</th>
                            <th>Class</th>
                            <th>Total Fees</th>
                            <th>Paid</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="font-weight: 500;">{{ $item['student']->full_name }}</td>
                                <td><code>{{ $item['student']->admission_number }}</code></td>
                                <td>{{ $item['student']->schoolClass->name ?? '—' }}</td>
                                <td>₦{{ number_format($item['total_fees'], 2) }}</td>
                                <td style="color: #0f5132;">₦{{ number_format($item['total_paid'], 2) }}</td>
                                <td style="font-weight: 600; color: #842029;">₦{{ number_format($item['balance'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="sb-empty-state">No outstanding balances. All students are fully paid!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
