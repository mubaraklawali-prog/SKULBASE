<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Cards - {{ $schoolClass->name }} - {{ $exam->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #1a1a2e; background: #f4f6f9; }

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

        .report-card-wrapper { margin: 80px auto 20px; }

        .report-card {
            max-width: 800px; margin: 0 auto 40px; background: #fff;
            border: 2px solid #e9ecef; border-radius: 4px; padding: 28px;
        }

        .school-header { text-align: center; border-bottom: 3px double #1a1a2e; padding-bottom: 12px; margin-bottom: 16px; }
        .school-header .school-name { font-size: 20px; font-weight: 700; color: #1a1a2e; text-transform: uppercase; letter-spacing: 1px; }
        .school-header .school-info { font-size: 10px; color: #6c757d; margin-top: 2px; }
        .school-header .report-title { font-size: 14px; font-weight: 600; color: #4f9cf7; margin-top: 10px; text-transform: uppercase; letter-spacing: 2px; }
        .school-logo { width: 70px; height: 70px; border-radius: 50%; object-fit: contain; margin-bottom: 6px; border: 2px solid #e9ecef; }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; background: #f8f9fa; }
        .info-table td { padding: 5px 10px; font-size: 11px; width: 50%; }
        .info-table td.label { font-weight: 600; color: #6c757d; font-size: 10px; text-transform: uppercase; width: 35%; }
        .info-table td.value { font-weight: 600; color: #1a1a2e; }

        .section-title { font-size: 12px; font-weight: 700; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 2px solid #4f9cf7; }

        .score-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .score-table th { background: #f0f2f5; padding: 5px 8px; font-size: 9px; font-weight: 600; color: #6c757d; text-transform: uppercase; text-align: left; border-bottom: 2px solid #dee2e6; }
        .score-table td { padding: 5px 8px; font-size: 10px; border-bottom: 1px solid #f0f0f0; }
        .score-table tr:last-child td { border-bottom: 2px solid #dee2e6; }

        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .summary-table td { padding: 6px; text-align: center; width: 33.33%; }
        .summary-table .value { font-size: 16px; font-weight: 700; color: #1a1a2e; display: block; }
        .summary-table .label { font-size: 9px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-top: 2px; }

        .grade-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .grade-table th { background: #f0f2f5; padding: 4px 8px; font-size: 9px; font-weight: 600; color: #6c757d; text-transform: uppercase; text-align: left; border-bottom: 1px solid #dee2e6; }
        .grade-table td { padding: 3px 8px; font-size: 10px; border-bottom: 1px solid #f0f0f0; }

        .comments-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .comments-table td { padding: 8px; background: #f8f9fa; width: 50%; vertical-align: top; }
        .comments-table .comment-label { font-size: 9px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block; }
        .comments-table .comment-text { font-size: 11px; color: #1a1a2e; line-height: 1.4; }

        .signature-table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        .signature-table td { padding: 30px 10px 0; text-align: center; width: 50%; border-top: 1px solid #1a1a2e; }
        .signature-table .sig-title { font-size: 10px; color: #6c757d; }

        .footer-note { text-align: center; font-size: 9px; color: #adb5bd; margin-top: 14px; padding-top: 8px; border-top: 1px solid #e9ecef; }

        .badge-pass { background: #d1e7dd; color: #0f5132; padding: 1px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .badge-fail { background: #f8d7da; color: #842029; padding: 1px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .badge-grade { background: #e7f1ff; color: #0d6efd; padding: 1px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }

        @media print {
            body { background: #fff; }
            .print-actions { display: none !important; }
            .report-card-wrapper { margin: 0; }
            .report-card {
                margin: 0 auto; border: none; border-radius: 0; box-shadow: none;
                max-width: 100%; padding: 16px; page-break-after: always;
            }
            .report-card:last-child { page-break-after: auto; }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">Print All</button>
        <a href="{{ route('results.report-cards.bulk') }}" class="btn-back">Back</a>
    </div>

    <div class="report-card-wrapper">
        @forelse($reportCards as $reportCard)
            @php
                $subjectScores = $subjectScoresMap[$reportCard->student_id] ?? [];
            @endphp
            <div class="report-card">
                <div class="school-header">
                    @if($reportCard->school->logo)
                        <img src="{{ asset('storage/' . $reportCard->school->logo) }}" alt="Logo" class="school-logo">
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

                <table class="info-table">
                    <tr>
                        <td class="label">Student Name</td>
                        <td class="value">{{ $reportCard->student->full_name ?? '' }}</td>
                        <td class="label">Admission No.</td>
                        <td class="value">{{ $reportCard->student->admission_number ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Class</td>
                        <td class="value">{{ $reportCard->schoolClass->name ?? '' }}{{ $reportCard->schoolClass->section ? ' - ' . $reportCard->schoolClass->section : '' }}</td>
                        <td class="label">Gender</td>
                        <td class="value">{{ ucfirst($reportCard->student->gender ?? '—') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Exam</td>
                        <td class="value">{{ $reportCard->exam->name ?? '' }}</td>
                        <td class="label">Term / Session</td>
                        <td class="value">{{ $reportCard->exam->term ?? '—' }} / {{ $reportCard->exam->session ?? '—' }}</td>
                    </tr>
                </table>

                @if(!empty($subjectScores) && count($subjectScores) > 0)
                    <div class="section-title">Subject Results</div>
                    <table class="score-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">S/N</th>
                                <th style="width: 30%;">Subject</th>
                                <th style="width: 15%;">Score (%)</th>
                                <th style="width: 15%;">Grade</th>
                                <th style="width: 20%;">Remark</th>
                                <th style="width: 15%;">Status</th>
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
                                            <span class="badge-grade">{{ $grade }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $remark ?? '—' }}</td>
                                    <td>
                                        @if($score >= 50)
                                            <span class="badge-pass">Pass</span>
                                        @else
                                            <span class="badge-fail">Fail</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <table class="summary-table">
                    <tr>
                        <td>
                            <span class="value" style="color: #0d6efd;">{{ number_format($reportCard->average_score, 1) }}%</span>
                            <span class="label">Average Score</span>
                        </td>
                        <td>
                            <span class="value">{{ $reportCard->overall_grade ?? '—' }}</span>
                            <span class="label">Overall Grade</span>
                        </td>
                        <td>
                            <span class="value" style="color: #0f5132;">{{ $reportCard->class_position ? $this->ordinal($reportCard->class_position) : '—' }}</span>
                            <span class="label">Class Position</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="value">{{ number_format($reportCard->total_score, 1) }}</span>
                            <span class="label">Total Score</span>
                        </td>
                        <td>
                            <span class="value" style="color: #0f5132;">{{ $reportCard->subjects_passed }}</span>
                            <span class="label">Subjects Passed</span>
                        </td>
                        <td>
                            <span class="value" style="color: #842029;">{{ $reportCard->subjects_failed }}</span>
                            <span class="label">Subjects Failed</span>
                        </td>
                    </tr>
                </table>

                @if($reportCard->attendance_percentage !== null)
                    <table class="summary-table" style="margin-bottom: 14px;">
                        <tr>
                            <td style="width: 100%;">
                                <span class="value" style="color: #0d6efd;">{{ number_format($reportCard->attendance_percentage, 1) }}%</span>
                                <span class="label">Attendance</span>
                            </td>
                        </tr>
                    </table>
                @endif

                @if($gradingRules->isNotEmpty())
                    <div class="section-title">Grading Key</div>
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
                <table class="comments-table">
                    <tr>
                        <td>
                            <span class="comment-label">Teacher's Comment</span>
                            <span class="comment-text">{{ $reportCard->teacher_comment ?? 'No comment' }}</span>
                        </td>
                        <td>
                            <span class="comment-label">Principal's Comment</span>
                            <span class="comment-text">{{ $reportCard->principal_comment ?? 'No comment' }}</span>
                        </td>
                    </tr>
                </table>

                <table class="signature-table">
                    <tr>
                        <td>
                            <div class="sig-title">Class Teacher's Signature / Date</div>
                        </td>
                        <td>
                            <div class="sig-title">Principal's Signature / Stamp / Date</div>
                        </td>
                    </tr>
                </table>

                <div class="footer-note">
                    {{ $reportCard->student->full_name ?? '' }} — {{ $reportCard->exam->name ?? '' }} — {{ $reportCard->schoolClass->name ?? '' }} | Generated {{ now()->format('F d, Y') }}
                </div>
            </div>
        @empty
            <div class="report-card" style="text-align: center; padding: 60px 32px;">
                <p style="font-size: 16px; color: #6c757d; margin: 0;">No published report cards found for this exam and class.</p>
                <a href="{{ route('results.report-cards.bulk') }}" style="display: inline-block; margin-top: 16px; color: #4f9cf7; font-weight: 600;">Go Back</a>
            </div>
        @endforelse
    </div>
</body>
</html>
