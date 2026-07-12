@extends('layouts.app')

@section('title', 'Class Summary Report - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Class Payment Summary</h2>
            <p class="text-muted mb-0">Payment overview per class</p>
        </div>
        <a href="{{ route('fees.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to Dashboard</a>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Students</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Expected</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Collected</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Outstanding</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Fully Paid</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summaries as $item)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $item['class']->name }}{{ $item['class']->section ? ' - ' . $item['class']->section : '' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $item['class']->students_count }}</td>
                                <td style="padding: 14px 20px;">₦{{ number_format($item['total_expected'], 2) }}</td>
                                <td style="padding: 14px 20px; color: #0f5132; font-weight: 600;">₦{{ number_format($item['total_collected'], 2) }}</td>
                                <td style="padding: 14px 20px; color: #842029; font-weight: 600;">₦{{ number_format($item['outstanding'], 2) }}</td>
                                <td style="padding: 14px 20px;">
                                    <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $item['students_fully_paid'] }}</span>
                                </td>
                                <td style="padding: 14px 20px;">
                                    @if($item['collection_rate'] >= 75)
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $item['collection_rate'] }}%</span>
                                    @elseif($item['collection_rate'] >= 40)
                                        <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $item['collection_rate'] }}%</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $item['collection_rate'] }}%</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">
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
