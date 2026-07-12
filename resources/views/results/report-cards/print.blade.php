<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card - {{ $reportCard->student->full_name ?? '' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1a1a2e; background: #f4f6f9; }

        .print-actions {
            position: fixed; top: 16px; right: 16px; z-index: 100;
            display: flex; gap: 8px;
        }
        .print-actions button, .print-actions a {
            padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;
            cursor: pointer; text-decoration: none; border: none;
        }
        .btn-print { background: #4f9cf7; color: #fff; }
        .btn-pdf { background: #0a1628; color: #fff; }
        .btn-back { background: #e9ecef; color: #333; }

        .report-card {
            max-width: 800px; margin: 80px auto 40px; background: #fff;
            border: 2px solid #e9ecef; border-radius: 4px; padding: 32px;
        }

        .school-header {
            text-align: center; border-bottom: 3px double #1a1a2e; padding-bottom: 16px; margin-bottom: 20px;
        }
        .school-header .school-name {
            font-size: 22px; font-weight: 700; color: #1a1a2e; text-transform: uppercase; letter-spacing: 1px;
        }
        .school-header .school-info {
            font-size: 11px; color: #6c757d; margin-top: 4px;
        }
        .school-header .report-title {
            font-size: 16px; font-weight: 600; color: #4f9cf7; margin-top: 12px; text-transform: uppercase; letter-spacing: 2px;
        }
        .school-logo {
            width: 80px; height: 80px; border-radius: 50%; object-fit: contain; margin-bottom: 8px;
            border: 2px solid #e9ecef;
        }

        .student-info-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
            padding: 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px;
        }
        .info-item label {
            display: block; font-size: 10px; font-weight: 600; color: #6c757d;
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;
        }
        .info-item span {
            font-size: 13px; font-weight: 600; color: #1a1a2e;
        }

        .section-title {
            font-size: 13px; font-weight: 700; color: #1a1a2e; text-transform: uppercase;
            letter-spacing: 0.5px; margin-bottom: 10px; padding-bottom: 6px;
            border-bottom: 2px solid #4f9cf7;
        }

        .score-table {
            width: 100%; border-collapse: collapse; margin-bottom: 20px;
        }
        .score-table th {
            background: #f0f2f5; padding: 8px 12px; font-size: 11px; font-weight: 600;
            color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;
            text-align: left; border-bottom: 2px solid #dee2e6;
        }
        .score-table td {
            padding: 8px 12px; font-size: 12px; border-bottom: 1px solid #f0f0f0;
        }
        .score-table tr:last-child td { border-bottom: 2px solid #dee2e6; }
        .score-table .total-row {
            font-weight: 700; background: #f8f9fa;
        }

        .summary-grid {
            display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 20px;
        }
        .summary-box {
            padding: 12px; background: #f8f9fa; border-radius: 8px; text-align: center;
        }
        .summary-box .value {
            font-size: 20px; font-weight: 700; color: #1a1a2e;
        }
        .summary-box .label {
            font-size: 10px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;
        }

        .grade-table {
            width: 100%; border-collapse: collapse; margin-bottom: 20px;
        }
        .grade-table th {
            background: #f0f2f5; padding: 6px 10px; font-size: 10px; font-weight: 600;
            color: #6c757d; text-transform: uppercase; text-align: left; border-bottom: 2px solid #dee2e6;
        }
        .grade-table td {
            padding: 5px 10px; font-size: 11px; border-bottom: 1px solid #f0f0f0;
        }

        .comments-section {
            display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;
        }
        .comment-box {
            padding: 12px; background: #f8f9fa; border-radius: 8px; min-height: 60px;
        }
        .comment-box .comment-label {
            font-size: 10px; font-weight: 600; color: #6c757d; text-transform: uppercase;
            letter-spacing: 0.5px; margin-bottom: 6px;
        }
        .comment-box .comment-text {
            font-size: 12px; color: #1a1a2e; line-height: 1.5;
        }

        .signature-section {
            display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 40px; padding-top: 20px;
        }
        .signature-line {
            text-align: center;
        }
        .signature-line .line {
            border-top: 1px solid #1a1a2e; margin-top: 40px; padding-top: 6px;
        }
        .signature-line .title {
            font-size: 11px; color: #6c757d;
        }

        .footer-note {
            text-align: center; font-size: 10px; color: #adb5bd; margin-top: 20px;
            padding-top: 12px; border-top: 1px solid #e9ecef;
        }

        @media print {
            body { background: #fff; }
            .print-actions { display: none !important; }
            .report-card {
                margin: 0; border: none; border-radius: 0; box-shadow: none;
                max-width: 100%; padding: 20px;
            }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">Print</button>
        <a href="{{ route('results.report-cards.pdf', $reportCard) }}" class="btn-pdf">Download PDF</a>
        <a href="{{ route('results.computations.show', $reportCard) }}" class="btn-back">Back</a>
    </div>

    <div class="report-card">
        <div class="school-header">
            @if($reportCard->school->logo)
                <img src="{{ asset('storage/' . $reportCard->school->logo) }}" alt="School Logo" class="school-logo">
            @endif
            <div class="school-name">{{ $reportCard->school->name ?? config('app.name', 'School') }}</div>
            @if($reportCard->school->address)
                <div class="school-info">{{ $reportCard->school->address }}{{ $reportCard->school->city ? ', ' . $reportCard->school->city : '' }}{{ $reportCard->school->state ? ', ' . $reportCard->school->state : '' }}</div>
            @endif
            @if($reportCard->school->phone || $reportCard->school->email)
                <div class="school-info">
                    @if($reportCard->school->phone)Tel: {{ $reportCard->school->phone }}@endif
                    @if($reportCard->school->phone && $reportCard->school->email) | @endif
                    @if($reportCard->school->email)Email: {{ $reportCard->school->email }}@endif
                </div>
            @endif
            <div class="report-title">Student Report Card</div>
        </div>

        <div class="student-info-grid">
            <div class="info-item">
                <label>Student Name</label>
                <span>{{ $reportCard->student->full_name ?? '' }}</span>
            </div>
            <div class="info-item">
                <label>Admission Number</label>
                <span>{{ $reportCard->student->admission_number ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>Class</label>
                <span>{{ $reportCard->schoolClass->name ?? '' }}{{ $reportCard->schoolClass->section ? ' - ' . $reportCard->schoolClass->section : '' }}</span>
            </div>
            <div class="info-item">
                <label>Gender</label>
                <span>{{ ucfirst($reportCard->student->gender ?? '—') }}</span>
            </div>
            <div class="info-item">
                <label>Exam</label>
                <span>{{ $reportCard->exam->name ?? '' }}</span>
            </div>
            <div class="info-item">
                <label>Term / Session</label>
                <span>{{ $reportCard->exam->term ?? '—' }} / {{ $reportCard->exam->session ?? '—' }}</span>
            </div>
        </div>

        @if(!empty($subjectScores) && count($subjectScores) > 0)
            <div class="section-title">Subject Results</div>
            <table class="score-table">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Subject</th>
                        <th>Score (%)</th>
                        <th>Grade</th>
                        <th>Remark</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sn = 1; @endphp
                    @foreach($subjectScores as $subjectId => $data)
                        @php
                            $score = $data['total_score'];
                            $grade = null;
                            $remark = null;
                            foreach($gradingRules as $rule) {
                                if($score >= $rule->min_score && $score <= $rule->max_score) {
                                    $grade = $rule->grade;
                                    $remark = $rule->remark;
                                    break;
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $sn++ }}</td>
                            <td style="font-weight: 600;">{{ $data['subject']->name ?? '—' }}</td>
                            <td style="font-weight: 600;">{{ number_format($score, 1) }}</td>
                            <td>
                                @if($grade)
                                    <span style="background: #e7f1ff; color: #0d6efd; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">{{ $grade }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td style="font-size: 11px;">{{ $remark ?? '—' }}</td>
                            <td>
                                @if($score >= 50)
                                    <span style="background: #d1e7dd; color: #0f5132; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Pass</span>
                                @else
                                    <span style="background: #f8d7da; color: #842029; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Fail</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="summary-grid">
            <div class="summary-box">
                <div class="value" style="color: #0d6efd;">{{ number_format($reportCard->average_score, 1) }}%</div>
                <div class="label">Average Score</div>
            </div>
            <div class="summary-box">
                <div class="value">{{ $reportCard->overall_grade ?? '—' }}</div>
                <div class="label">Overall Grade</div>
            </div>
            <div class="summary-box">
                <div class="value" style="color: #0f5132;">{{ $reportCard->class_position ? $this->ordinal($reportCard->class_position) : '—' }}</div>
                <div class="label">Class Position</div>
            </div>
            <div class="summary-box">
                <div class="value">{{ number_format($reportCard->total_score, 1) }}</div>
                <div class="label">Total Score</div>
            </div>
            <div class="summary-box">
                <div class="value" style="color: #0f5132;">{{ $reportCard->subjects_passed }}</div>
                <div class="label">Subjects Passed</div>
            </div>
            <div class="summary-box">
                <div class="value" style="color: #842029;">{{ $reportCard->subjects_failed }}</div>
                <div class="label">Subjects Failed</div>
            </div>
        </div>

        @if($reportCard->attendance_percentage !== null)
            <div class="summary-grid" style="grid-template-columns: 1fr;">
                <div class="summary-box">
                    <div class="value" style="color: #0d6efd;">{{ number_format($reportCard->attendance_percentage, 1) }}%</div>
                    <div class="label">Attendance</div>
                </div>
            </div>
        @endif

        <div class="section-title">Grading Key</div>
        @if($gradingRules->isNotEmpty())
            <table class="grade-table">
                <thead>
                    <tr>
                        <th>Score Range</th>
                        <th>Grade</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gradingRules as $rule)
                        <tr>
                            <td>{{ number_format($rule->min_score, 0) }}% - {{ number_format($rule->max_score, 0) }}%</td>
                            <td style="font-weight: 600;">{{ $rule->grade }}</td>
                            <td>{{ $rule->remark }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="section-title">Comments</div>
        <div class="comments-section">
            <div class="comment-box">
                <div class="comment-label">Teacher's Comment</div>
                <div class="comment-text">{{ $reportCard->teacher_comment ?? 'No comment' }}</div>
            </div>
            <div class="comment-box">
                <div class="comment-label">Principal's Comment</div>
                <div class="comment-text">{{ $reportCard->principal_comment ?? 'No comment' }}</div>
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-line">
                <div class="line"></div>
                <div class="title">Class Teacher's Signature / Date</div>
            </div>
            <div class="signature-line">
                <div class="line"></div>
                <div class="title">Principal's Signature / Stamp / Date</div>
            </div>
        </div>

        <div class="footer-note">
            This report card was generated on {{ now()->format('F d, Y \a\t h:i A') }} — {{ $reportCard->school->name ?? config('app.name') }}
        </div>
    </div>
</body>
</html>
