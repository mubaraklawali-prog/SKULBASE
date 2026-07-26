@extends('layouts.app')

@section('title', 'Approval Reports - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Approval Reports</h2>
            <p>View result approval reports and audit history</p>
        </div>
        <a href="{{ route('results.approvals.dashboard') }}" class="sb-btn sb-btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body sb-card-body">
            <div class="d-flex gap-2 mb-0">
                <a href="{{ route('results.approvals.reports', ['type' => 'published']) }}"
                   class="sb-badge {{ $type === 'published' ? 'sb-badge-published' : 'sb-badge-draft' }}">
                    Published
                </a>
                <a href="{{ route('results.approvals.reports', ['type' => 'pending']) }}"
                   class="sb-badge {{ $type === 'pending' ? 'sb-badge-pending' : 'sb-badge-draft' }}">
                    Pending Approval
                </a>
                <a href="{{ route('results.approvals.reports', ['type' => 'rejected']) }}"
                   class="sb-badge {{ $type === 'rejected' ? 'sb-badge-rejected' : 'sb-badge-draft' }}">
                    Rejected
                </a>
                <a href="{{ route('results.approvals.reports', ['type' => 'history']) }}"
                   class="sb-badge {{ $type === 'history' ? 'sb-badge-info' : 'sb-badge-draft' }}">
                    Audit History
                </a>
            </div>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            @if($type === 'history')
                @if($reportCards->isEmpty())
                    <div class="sb-empty-state">
                        <p>No audit logs found.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="sb-table table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Student</th>
                                    <th>Exam</th>
                                    <th>Status Change</th>
                                    <th>Performed By</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportCards as $log)
                                    <tr>
                                        <td class="small text-muted">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($log->action === 'submitted')
                                                <span class="sb-badge sb-badge-info" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">Submitted</span>
                                            @elseif($log->action === 'approved')
                                                <span class="sb-badge sb-badge-pending" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">Approved</span>
                                            @elseif($log->action === 'published')
                                                <span class="sb-badge sb-badge-published" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">Published</span>
                                            @elseif($log->action === 'rejected')
                                                <span class="sb-badge sb-badge-rejected" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">Rejected</span>
                                            @else
                                                <span class="sb-badge sb-badge-draft" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">{{ ucfirst($log->action) }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-medium">{{ $log->studentReportCard->student->full_name ?? '—' }}</td>
                                        <td>{{ $log->studentReportCard->exam->name ?? '—' }}</td>
                                        <td>
                                            <span class="text-muted">{{ $log->old_status ?? '—' }}</span>
                                            <span class="text-muted"> → </span>
                                            <span class="fw-semibold">{{ $log->new_status }}</span>
                                        </td>
                                        <td>{{ $log->performedByUser->name ?? 'System' }}</td>
                                        <td class="small text-muted">{{ $log->remarks ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $reportCards->links() }}
                    </div>
                @endif
            @else
                @if($reportCards->isEmpty())
                    <div class="sb-empty-state">
                        <p>No report cards found.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="sb-table table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Exam</th>
                                    <th>Class</th>
                                    <th>Average</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportCards as $card)
                                    <tr>
                                        <td class="fw-medium">{{ $card->student->full_name ?? '—' }}</td>
                                        <td>{{ $card->exam->name ?? '—' }}</td>
                                        <td>{{ $card->schoolClass->name ?? '—' }}{{ $card->schoolClass->section ? ' - ' . $card->schoolClass->section : '' }}</td>
                                        <td class="fw-semibold">{{ number_format($card->average_score, 1) }}%</td>
                                        <td>
                                            <span class="sb-badge sb-badge-info" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">{{ $card->overall_grade ?? '—' }}</span>
                                        </td>
                                        <td>
                                            @if($card->status === 'published')
                                                <span class="sb-badge sb-badge-published" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">Published</span>
                                            @elseif($card->status === 'approved')
                                                <span class="sb-badge sb-badge-pending" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">Approved</span>
                                            @elseif($card->status === 'submitted')
                                                <span class="sb-badge sb-badge-info" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">Submitted</span>
                                            @elseif($card->status === 'rejected')
                                                <span class="sb-badge sb-badge-rejected" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">Rejected</span>
                                            @else
                                                <span class="sb-badge sb-badge-draft" style="padding: 4px 10px; border-radius: 12px; font-size: 11px;">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('results.computations.show', $card) }}" class="sb-link">View</a>
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

                    <div class="mt-3">
                        {{ $reportCards->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
