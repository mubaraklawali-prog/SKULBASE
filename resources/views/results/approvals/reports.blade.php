@extends('layouts.app')

@section('title', 'Approval Reports - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Approval Reports</h2>
            <p class="text-muted mb-0">View result approval reports and audit history</p>
        </div>
        <a href="{{ route('results.approvals.dashboard') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 24px;">
            <div class="d-flex gap-2 mb-0">
                <a href="{{ route('results.approvals.reports', ['type' => 'published']) }}"
                   style="padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; {{ $type === 'published' ? 'background: #d1e7dd; color: #0f5132;' : 'background: #f0f2f5; color: #6c757d;' }}">
                    Published
                </a>
                <a href="{{ route('results.approvals.reports', ['type' => 'pending']) }}"
                   style="padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; {{ $type === 'pending' ? 'background: #fff3cd; color: #664d03;' : 'background: #f0f2f5; color: #6c757d;' }}">
                    Pending Approval
                </a>
                <a href="{{ route('results.approvals.reports', ['type' => 'rejected']) }}"
                   style="padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; {{ $type === 'rejected' ? 'background: #f8d7da; color: #842029;' : 'background: #f0f2f5; color: #6c757d;' }}">
                    Rejected
                </a>
                <a href="{{ route('results.approvals.reports', ['type' => 'history']) }}"
                   style="padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; {{ $type === 'history' ? 'background: #e7f1ff; color: #0d6efd;' : 'background: #f0f2f5; color: #6c757d;' }}">
                    Audit History
                </a>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            @if($type === 'history')
                @if($reportCards->isEmpty())
                    <div style="text-align: center; padding: 40px;">
                        <p class="text-muted" style="margin: 0;">No audit logs found.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Action</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Exam</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status Change</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Performed By</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportCards as $log)
                                    <tr>
                                        <td style="padding: 12px 16px; color: #6c757d; font-size: 13px;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                        <td style="padding: 12px 16px;">
                                            @if($log->action === 'submitted')
                                                <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Submitted</span>
                                            @elseif($log->action === 'approved')
                                                <span style="background: #fff3cd; color: #664d03; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Approved</span>
                                            @elseif($log->action === 'published')
                                                <span style="background: #d1e7dd; color: #0f5132; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Published</span>
                                            @elseif($log->action === 'rejected')
                                                <span style="background: #f8d7da; color: #842029; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Rejected</span>
                                            @else
                                                <span style="background: #f0f2f5; color: #6c757d; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">{{ ucfirst($log->action) }}</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px 16px; font-weight: 500;">{{ $log->studentReportCard->student->full_name ?? '—' }}</td>
                                        <td style="padding: 12px 16px; color: #6c757d;">{{ $log->studentReportCard->exam->name ?? '—' }}</td>
                                        <td style="padding: 12px 16px;">
                                            <span style="color: #6c757d;">{{ $log->old_status ?? '—' }}</span>
                                            <span style="color: #6c757d;"> → </span>
                                            <span style="font-weight: 600;">{{ $log->new_status }}</span>
                                        </td>
                                        <td style="padding: 12px 16px; color: #6c757d;">{{ $log->performedByUser->name ?? 'System' }}</td>
                                        <td style="padding: 12px 16px; color: #6c757d; font-size: 12px;">{{ $log->remarks ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 16px;">
                        {{ $reportCards->links() }}
                    </div>
                @endif
            @else
                @if($reportCards->isEmpty())
                    <div style="text-align: center; padding: 40px;">
                        <p class="text-muted" style="margin: 0;">No report cards found.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Exam</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Average</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Grade</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                    <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportCards as $card)
                                    <tr>
                                        <td style="padding: 12px 16px; font-weight: 500;">{{ $card->student->full_name ?? '—' }}</td>
                                        <td style="padding: 12px 16px; color: #6c757d;">{{ $card->exam->name ?? '—' }}</td>
                                        <td style="padding: 12px 16px; color: #6c757d;">{{ $card->schoolClass->name ?? '—' }}{{ $card->schoolClass->section ? ' - ' . $card->schoolClass->section : '' }}</td>
                                        <td style="padding: 12px 16px; font-weight: 600;">{{ number_format($card->average_score, 1) }}%</td>
                                        <td style="padding: 12px 16px;">
                                            <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">{{ $card->overall_grade ?? '—' }}</span>
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            @if($card->status === 'published')
                                                <span style="background: #d1e7dd; color: #0f5132; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Published</span>
                                            @elseif($card->status === 'approved')
                                                <span style="background: #fff3cd; color: #664d03; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Approved</span>
                                            @elseif($card->status === 'submitted')
                                                <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Submitted</span>
                                            @elseif($card->status === 'rejected')
                                                <span style="background: #f8d7da; color: #842029; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Rejected</span>
                                            @else
                                                <span style="background: #f0f2f5; color: #6c757d; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Draft</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <a href="{{ route('results.computations.show', $card) }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none; font-size: 13px;">View</a>
                                        </td>
                                    </tr>
                                    @if($card->rejection_reason)
                                        <tr>
                                            <td colspan="7" style="padding: 0 16px 8px; background: #fff5f5;">
                                                <small style="color: #842029;"><strong>Rejection reason:</strong> {{ $card->rejection_reason }}</small>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 16px;">
                        {{ $reportCards->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
