@extends('layouts.app')

@section('title', 'Class Summary Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Class Payment Summary</h2>
            <p class="text-muted mb-0">Payment overview per class</p>
        </div>
        <a href="{{ route('fees.dashboard') }}" class="sb-btn sb-btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Students</th>
                            <th>Expected</th>
                            <th>Collected</th>
                            <th>Outstanding</th>
                            <th>Fully Paid</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summaries as $item)
                            <tr>
                                <td style="font-weight: 500;">{{ $item['class']->name }}{{ $item['class']->section ? ' - ' . $item['class']->section : '' }}</td>
                                <td>{{ $item['class']->students_count }}</td>
                                <td>₦{{ number_format($item['total_expected'], 2) }}</td>
                                <td style="color: #0f5132; font-weight: 600;">₦{{ number_format($item['total_collected'], 2) }}</td>
                                <td style="color: #842029; font-weight: 600;">₦{{ number_format($item['outstanding'], 2) }}</td>
                                <td>
                                    <span class="sb-badge sb-badge-active">{{ $item['students_fully_paid'] }}</span>
                                </td>
                                <td>
                                    @if($item['collection_rate'] >= 75)
                                        <span class="sb-badge sb-badge-active">{{ $item['collection_rate'] }}%</span>
                                    @elseif($item['collection_rate'] >= 40)
                                        <span class="sb-badge sb-badge-pending">{{ $item['collection_rate'] }}%</span>
                                    @else
                                        <span class="sb-badge sb-badge-rejected">{{ $item['collection_rate'] }}%</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="sb-empty-state">
                                    <p style="margin: 0; font-size: 15px;">No classes found.</p>
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
