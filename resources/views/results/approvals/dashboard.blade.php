@extends('layouts.app')

@section('title', 'Result Approval Workflow - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Result Approval Workflow</h2>
            <p class="text-muted mb-0">Manage the approval and publishing of student results</p>
        </div>
        <a href="{{ route('results.approvals.reports') }}" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">View Reports</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <a href="{{ route('results.approvals.dashboard', ['status' => 'draft']) }}" style="text-decoration: none;">
                <div class="card stat-card" style="height: 100%; {{ request('status') === 'draft' ? 'border: 2px solid #6c757d;' : '' }}">
                    <div class="card-body" style="padding: 16px; text-align: center;">
                        <p class="stat-number" style="color: #6c757d; font-size: 24px;">{{ number_format($statusCounts['draft']) }}</p>
                        <p class="stat-label">Draft</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="{{ route('results.approvals.dashboard', ['status' => 'submitted']) }}" style="text-decoration: none;">
                <div class="card stat-card" style="height: 100%; {{ request('status') === 'submitted' ? 'border: 2px solid #0d6efd;' : '' }}">
                    <div class="card-body" style="padding: 16px; text-align: center;">
                        <p class="stat-number" style="color: #0d6efd; font-size: 24px;">{{ number_format($statusCounts['submitted']) }}</p>
                        <p class="stat-label">Submitted</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="{{ route('results.approvals.dashboard', ['status' => 'approved']) }}" style="text-decoration: none;">
                <div class="card stat-card" style="height: 100%; {{ request('status') === 'approved' ? 'border: 2px solid #664d03;' : '' }}">
                    <div class="card-body" style="padding: 16px; text-align: center;">
                        <p class="stat-number" style="color: #664d03; font-size: 24px;">{{ number_format($statusCounts['approved']) }}</p>
                        <p class="stat-label">Approved</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="{{ route('results.approvals.dashboard', ['status' => 'published']) }}" style="text-decoration: none;">
                <div class="card stat-card" style="height: 100%; {{ request('status') === 'published' ? 'border: 2px solid #0f5132;' : '' }}">
                    <div class="card-body" style="padding: 16px; text-align: center;">
                        <p class="stat-number" style="color: #0f5132; font-size: 24px;">{{ number_format($statusCounts['published']) }}</p>
                        <p class="stat-label">Published</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="{{ route('results.approvals.dashboard', ['status' => 'rejected']) }}" style="text-decoration: none;">
                <div class="card stat-card" style="height: 100%; {{ request('status') === 'rejected' ? 'border: 2px solid #dc3545;' : '' }}">
                    <div class="card-body" style="padding: 16px; text-align: center;">
                        <p class="stat-number" style="color: #dc3545; font-size: 24px;">{{ number_format($statusCounts['rejected']) }}</p>
                        <p class="stat-label">Rejected</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="{{ route('results.approvals.dashboard') }}" style="text-decoration: none;">
                <div class="card stat-card" style="height: 100%;">
                    <div class="card-body" style="padding: 16px; text-align: center;">
                        <p class="stat-number" style="color: #1a1a2e; font-size: 24px;">{{ number_format(array_sum($statusCounts)) }}</p>
                        <p class="stat-label">Total</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 24px;">
            <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Filter & Bulk Actions</h5>
            <form method="POST" action="{{ route('results.approvals.bulk-action') }}" id="bulkActionForm">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Exam</label>
                        <select name="exam_id" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- All Exams --</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Class</label>
                        <select name="school_class_id" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- All Classes --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Bulk Action</label>
                        <select name="action" required style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;">
                            <option value="">-- Select Action --</option>
                            <option value="submit">Submit All (Draft → Submitted)</option>
                            <option value="approve">Approve All (Submitted → Approved)</option>
                            <option value="publish">Publish All (Approved → Published)</option>
                            <option value="revert">Revert All to Draft</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3" id="rejectionReasonGroup" style="display: none;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 6px;">Rejection Reason</label>
                        <input type="text" name="rejection_reason" style="width: 100%; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;" placeholder="Enter reason...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" onclick="return confirm('Are you sure you want to perform this bulk action?')" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 500; border: none; cursor: pointer;">Execute</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 style="font-weight: 600; color: #1a1a2e; margin: 0;">Report Cards</h5>
                        <div class="d-flex gap-2">
                            <form method="GET" action="{{ route('results.approvals.dashboard') }}" style="display: flex; gap: 8px;">
                                <select name="exam_id" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #dee2e6; font-size: 13px;">
                                    <option value="">All Exams</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                                <select name="school_class_id" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #dee2e6; font-size: 13px;">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #dee2e6; background: #fff; font-size: 13px; cursor: pointer;">Filter</button>
                            </form>
                        </div>
                    </div>

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
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                        <th style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
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
                                                @if($card->status === 'published')
                                                    <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Published</span>
                                                @elseif($card->status === 'approved')
                                                    <span style="background: #fff3cd; color: #664d03; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Approved</span>
                                                @elseif($card->status === 'submitted')
                                                    <span style="background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Submitted</span>
                                                @elseif($card->status === 'rejected')
                                                    <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Rejected</span>
                                                @else
                                                    <span style="background: #f0f2f5; color: #6c757d; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Draft</span>
                                                @endif
                                            </td>
                                            <td style="padding: 12px 16px; text-align: right;">
                                                <div class="d-flex gap-1" style="justify-content: flex-end;">
                                                    @if($card->status === 'draft')
                                                        <form method="POST" action="{{ route('results.approvals.submit', $card) }}">
                                                            @csrf
                                                            <button type="submit" style="background: #e7f1ff; color: #0d6efd; padding: 4px 10px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;">Submit</button>
                                                        </form>
                                                    @endif
                                                    @if($card->status === 'submitted')
                                                        <form method="POST" action="{{ route('results.approvals.approve', $card) }}">
                                                            @csrf
                                                            <button type="submit" style="background: #fff3cd; color: #664d03; padding: 4px 10px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;">Approve</button>
                                                        </form>
                                                        <form method="POST" action="{{ route('results.approvals.reject', $card) }}" class="reject-form">
                                                            @csrf
                                                            <input type="hidden" name="rejection_reason" class="reject-reason" value="">
                                                            <button type="submit" onclick="event.preventDefault(); var reason = prompt('Rejection reason:'); if(reason){this.form.querySelector('.reject-reason').value=reason;this.form.submit();}" style="background: #f8d7da; color: #842029; padding: 4px 10px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;">Reject</button>
                                                        </form>
                                                    @endif
                                                    @if($card->status === 'approved')
                                                        <form method="POST" action="{{ route('results.approvals.publish', $card) }}">
                                                            @csrf
                                                            <button type="submit" style="background: #d1e7dd; color: #0f5132; padding: 4px 10px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;">Publish</button>
                                                        </form>
                                                        <form method="POST" action="{{ route('results.approvals.reject', $card) }}" class="reject-form">
                                                            @csrf
                                                            <input type="hidden" name="rejection_reason" class="reject-reason" value="">
                                                            <button type="submit" onclick="event.preventDefault(); var reason = prompt('Rejection reason:'); if(reason){this.form.querySelector('.reject-reason').value=reason;this.form.submit();}" style="background: #f8d7da; color: #842029; padding: 4px 10px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;">Reject</button>
                                                        </form>
                                                    @endif
                                                    @if($card->status === 'published')
                                                        <form method="POST" action="{{ route('results.approvals.unpublish', $card) }}">
                                                            @csrf
                                                            <button type="submit" onclick="return confirm('Unpublish this report card?')" style="background: #f0f2f5; color: #6c757d; padding: 4px 10px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;">Unpublish</button>
                                                        </form>
                                                    @endif
                                                    @if(in_array($card->status, ['submitted', 'approved', 'published', 'rejected']))
                                                        <form method="POST" action="{{ route('results.approvals.revert', $card) }}">
                                                            @csrf
                                                            <button type="submit" onclick="return confirm('Revert to draft?')" style="background: #f0f2f5; color: #6c757d; padding: 4px 10px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;">Revert</button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('results.computations.show', $card) }}" style="color: #4f9cf7; padding: 4px 10px; font-size: 12px; font-weight: 600; text-decoration: none;">View</a>
                                                </div>
                                            </td>
                                        </tr>
                                        @if($card->rejection_reason)
                                            <tr>
                                                <td colspan="6" style="padding: 0 16px 8px; background: #fff5f5;">
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
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Workflow</h5>
                    <div class="d-flex flex-column gap-2">
                        <div style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #f0f2f5; border-radius: 8px;">
                            <span style="background: #f0f2f5; color: #6c757d; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">1</span>
                            <span style="font-size: 13px; font-weight: 500;">Draft — Results can be edited</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #f0f2f5; border-radius: 8px;">
                            <span style="background: #e7f1ff; color: #0d6efd; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">2</span>
                            <span style="font-size: 13px; font-weight: 500;">Submitted — Scores locked</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #f0f2f5; border-radius: 8px;">
                            <span style="background: #fff3cd; color: #664d03; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">3</span>
                            <span style="font-size: 13px; font-weight: 500;">Approved — Ready for publication</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #f0f2f5; border-radius: 8px;">
                            <span style="background: #d1e7dd; color: #0f5132; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">4</span>
                            <span style="font-size: 13px; font-weight: 500;">Published — Printable & visible</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #fff5f5; border-radius: 8px;">
                            <span style="background: #f8d7da; color: #842029; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">R</span>
                            <span style="font-size: 13px; font-weight: 500;">Rejected — Returns to Draft</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stat-card">
                <div class="card-body">
                    <h5 style="font-weight: 600; margin-bottom: 16px; color: #1a1a2e;">Recent Activity</h5>
                    @if($recentLogs->isEmpty())
                        <p class="text-muted" style="margin: 0; font-size: 13px;">No recent activity.</p>
                    @else
                        @foreach($recentLogs as $log)
                            <div style="padding: 8px 0; border-bottom: 1px solid #f0f0f0; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($log->action === 'submitted')
                                        <span style="background: #e7f1ff; color: #0d6efd; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600;">SUBMIT</span>
                                    @elseif($log->action === 'approved')
                                        <span style="background: #fff3cd; color: #664d03; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600;">APPROVE</span>
                                    @elseif($log->action === 'published')
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600;">PUBLISH</span>
                                    @elseif($log->action === 'rejected')
                                        <span style="background: #f8d7da; color: #842029; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600;">REJECT</span>
                                    @else
                                        <span style="background: #f0f2f5; color: #6c757d; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600;">{{ strtoupper($log->action) }}</span>
                                    @endif
                                    <span style="font-size: 12px; color: #6c757d;">{{ $log->performedByUser->name ?? 'System' }}</span>
                                </div>
                                <p style="font-size: 12px; color: #333; margin: 2px 0 0;">{{ $log->studentReportCard->student->full_name ?? '—' }} — {{ $log->old_status }} → {{ $log->new_status }}</p>
                                <small style="color: #adb5bd;">{{ $log->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
