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
            <a href="{{ route('reports.export.outstanding.csv', request()->query()) }}" class="btn" style="background: #198754; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Export CSV</a>
            <a href="{{ route('reports.export.outstanding.pdf', request()->query()) }}" class="btn" style="background: #dc3545; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Export PDF</a>
            <a href="{{ route('reports.dashboard', array_filter(['school_id' => request('school_id')])) }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back</a>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.fees.outstanding') }}" class="card stat-card mb-4">
        @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Filter by Class</label>
                    <select name="class_id" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">Filter</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">{{ number_format($count) }}</p>
                    <p class="stat-label">Students with Balance</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body text-center" style="padding: 20px;">
                    <p class="stat-number" style="font-size: 24px; color: #842029;">₦{{ number_format($total_outstanding, 2) }}</p>
                    <p class="stat-label">Total Outstanding</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">#</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Adm. No.</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Total Fees</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Paid</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $index + 1 }}</td>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $item['student']->full_name }}</td>
                                <td style="padding: 14px 20px;"><code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $item['student']->admission_number }}</code></td>
                                <td style="padding: 14px 20px;">{{ $item['student']->schoolClass->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">₦{{ number_format($item['total_fees'], 2) }}</td>
                                <td style="padding: 14px 20px; color: #0f5132;">₦{{ number_format($item['total_paid'], 2) }}</td>
                                <td style="padding: 14px 20px; font-weight: 600; color: #842029;">₦{{ number_format($item['balance'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">No outstanding balances. All students are fully paid!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
