<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Card - {{ $reportCard->student->full_name ?? '' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #1a1a2e; }

        .school-header { text-align: center; border-bottom: 3px double #1a1a2e; padding-bottom: 12px; margin-bottom: 16px; }
        .school-header .school-name { font-size: 20px; font-weight: 700; color: #1a1a2e; text-transform: uppercase; letter-spacing: 1px; }
        .school-header .school-info { font-size: 10px; color: #6c757d; margin-top: 2px; }
        .school-header .report-title { font-size: 14px; font-weight: 600; color: var(--primary); margin-top: 10px; text-transform: uppercase; letter-spacing: 2px; }
        .school-logo { width: 70px; height: 70px; object-fit: contain; margin-bottom: 6px; }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; background: #f8f9fa; }
        .info-table td { padding: 6px 12px; font-size: 11px; width: 50%; }
        .info-table td.label { font-weight: 600; color: #6c757d; font-size: 10px; text-transform: uppercase; width: 35%; }
        .info-table td.value { font-weight: 600; color: #1a1a2e; }

        .section-title {
            font-size: 12px; font-weight: 700; color: #1a1a2e; text-transform: uppercase;
            letter-spacing: 0.5px; margin-bottom: 8px; padding-bottom: 4px;
            border-bottom: 2px solid var(--primary);
        }

        .score-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .score-table th {
            background: #f0f2f5; padding: 6px 10px; font-size: 10px; font-weight: 600;
            color: #6c757d; text-transform: uppercase; text-align: left; border-bottom: 2px solid #dee2e6;
        }
        .score-table th.col-sn { width: 5%; }
        .score-table th.col-subject { width: 30%; }
        .score-table th.col-score { width: 15%; }
        .score-table th.col-grade { width: 15%; }
        .score-table th.col-remark { width: 20%; }
        .score-table th.col-status { width: 15%; }
        .score-table td { padding: 6px 10px; font-size: 11px; border-bottom: 1px solid #f0f0f0; }
        .score-table tr:last-child td { border-bottom: 2px solid #dee2e6; }
        .score-table .text-bold { font-weight: 600; }

        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .summary-table td { padding: 8px; text-align: center; width: 33.33%; }
        .summary-table td.col-full { width: 100%; }
        .summary-table .value { font-size: 18px; font-weight: 700; color: #1a1a2e; display: block; }
        .summary-table .label { font-size: 9px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-top: 2px; }

        .grade-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .grade-table th {
            background: #f0f2f5; padding: 4px 8px; font-size: 9px; font-weight: 600;
            color: #6c757d; text-transform: uppercase; text-align: left; border-bottom: 1px solid #dee2e6;
        }
        .grade-table td { padding: 4px 8px; font-size: 10px; border-bottom: 1px solid #f0f0f0; }
        .grade-table .text-bold { font-weight: 600; }

        .comments-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .comments-table td { padding: 10px; background: #f8f9fa; width: 50%; vertical-align: top; }
        .comments-table .comment-label { font-size: 9px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block; }
        .comments-table .comment-text { font-size: 11px; color: #1a1a2e; line-height: 1.4; }

        .signature-table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        .signature-table td { padding: 30px 10px 0; text-align: center; width: 50%; border-top: 1px solid #1a1a2e; }
        .signature-table .sig-title { font-size: 10px; color: #6c757d; }

        .footer-note { text-align: center; font-size: 9px; color: #adb5bd; margin-top: 16px; padding-top: 8px; border-top: 1px solid #e9ecef; }

        .badge-pass { background: #d1e7dd; color: #0f5132; padding: 1px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .badge-fail { background: #f8d7da; color: #842029; padding: 1px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .badge-grade { background: #e7f1ff; color: #0d6efd; padding: 1px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }

        .text-primary { color: #0d6efd; }
        .text-success { color: #0f5132; }
        .text-danger { color: #842029; }
    </style>
</head>
<body>
    <div class="school-header">
        @if($reportCard->school->logo)
            <img src="{{ public_path('storage/' . $reportCard->school->logo) }}" alt="Logo" class="school-logo">
        @endif
        <div class="school-name">{{ $reportCard->school->name ?? config('app.name', 'School') }}</div>
        @if($reportCard->school->motto)
            <div class="school-info" style="font-style: italic;">"{{ $reportCard->school->motto }}"</div>
        @endif
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
                    <th class="col-sn">S/N</th>
                    <th class="col-subject">Subject</th>
                    <th class="col-score">Score (%)</th>
                    <th class="col-grade">Grade</th>
                    <th class="col-remark">Remark</th>
                    <th class="col-status">Status</th>
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
                        <td class="text-bold">{{ $data['subject']->name ?? '—' }}</td>
                        <td class="text-bold">{{ number_format($score, 1) }}</td>
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
                <span class="value text-primary">{{ number_format($reportCard->average_score, 1) }}%</span>
                <span class="label">Average Score</span>
            </td>
            <td>
                <span class="value">{{ $reportCard->overall_grade ?? '—' }}</span>
                <span class="label">Overall Grade</span>
            </td>
            <td>
                <span class="value text-success">@if($reportCard->class_position)@php $pos = $reportCard->class_position; $suffix = match(true) { $pos % 100 >= 11 && $pos % 100 <= 13 => 'th', $pos % 10 === 1 => 'st', $pos % 10 === 2 => 'nd', $pos % 10 === 3 => 'rd', default => 'th' }; @endphp{{ $pos . $suffix }}@else—@endif</span>
                <span class="label">Class Position</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="value">{{ number_format($reportCard->total_score, 1) }}</span>
                <span class="label">Total Score</span>
            </td>
            <td>
                <span class="value text-success">{{ $reportCard->subjects_passed }}</span>
                <span class="label">Subjects Passed</span>
            </td>
            <td>
                <span class="value text-danger">{{ $reportCard->subjects_failed }}</span>
                <span class="label">Subjects Failed</span>
            </td>
        </tr>
    </table>

    @if($reportCard->attendance_percentage !== null)
        <table class="summary-table" style="margin-bottom: 16px;">
            <tr>
                <td class="col-full">
                    <span class="value text-primary">{{ number_format($reportCard->attendance_percentage, 1) }}%</span>
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
                        <td class="text-bold">{{ $rule->grade }}</td>
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
        This report card was generated on {{ now()->format('F d, Y \a\t h:i A') }} — {{ $reportCard->school->name ?? config('app.name') }}
    </div>
</body>
</html>
