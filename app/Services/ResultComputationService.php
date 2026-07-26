<?php

namespace App\Services;

use App\Models\AssessmentType;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\GradingSystem;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentReportCard;
use App\Models\StudentResult;
use Illuminate\Support\Facades\DB;

class ResultComputationService
{
    private int $passMark = 50;

    public function setPassMark(int $passMark): void
    {
        $this->passMark = $passMark;
    }

    public function computeForClass(Exam $exam, SchoolClass $schoolClass): array
    {
        $schoolId = $schoolClass->school_id;

        $students = Student::where('school_class_id', $schoolClass->id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        if ($students->isEmpty()) {
            return ['computed' => 0, 'message' => 'No active students found in this class.'];
        }

        $assessmentTypes = AssessmentType::where('school_id', $schoolId)
            ->where('status', true)
            ->get();

        $gradingRules = GradingSystem::where('school_id', $schoolId)
            ->orderByDesc('min_score')
            ->get();

        $results = StudentResult::where('exam_id', $exam->id)
            ->where('school_class_id', $schoolClass->id)
            ->with('subject', 'assessmentType')
            ->get();

        if ($results->isEmpty()) {
            return ['computed' => 0, 'message' => 'No scores found for this exam and class.'];
        }

        $resultsByStudent = $results->groupBy('student_id');

        $attendanceMap = $this->batchAttendancePercentages(
            array_keys($resultsByStudent->toArray()),
            $schoolClass->id,
            $exam
        );

        $studentAverages = [];

        DB::transaction(function () use ($exam, $schoolClass, $resultsByStudent, $assessmentTypes, $gradingRules, $attendanceMap, &$studentAverages) {
            foreach ($resultsByStudent as $studentId => $studentResults) {
                $computed = $this->computeStudentResult(
                    $exam,
                    $schoolClass,
                    $studentId,
                    $studentResults,
                    $assessmentTypes,
                    $gradingRules,
                    $attendanceMap
                );

                if ($computed) {
                    $studentAverages[$studentId] = $computed['average_score'];
                }
            }

            $this->calculatePositions($exam, $schoolClass, $studentAverages);
        });

        $computedCount = StudentReportCard::where('exam_id', $exam->id)
            ->where('school_class_id', $schoolClass->id)
            ->count();

        return [
            'computed' => $computedCount,
            'message' => "Successfully computed results for {$computedCount} students.",
        ];
    }

    private function computeStudentResult(
        Exam $exam,
        SchoolClass $schoolClass,
        int $studentId,
        $studentResults,
        $assessmentTypes,
        $gradingRules,
        array $attendanceMap
    ): ?array {
        $subjectGroups = $studentResults->groupBy('subject_id');

        if ($subjectGroups->isEmpty()) {
            return null;
        }

        $totalPercentage = $assessmentTypes->sum('percentage');
        $subjectScores = [];
        $totalScore = 0;
        $subjectsPassed = 0;
        $subjectsFailed = 0;

        foreach ($subjectGroups as $subjectId => $subjectResults) {
            $subjectTotal = 0;

            foreach ($subjectResults as $result) {
                $assessmentType = $assessmentTypes->firstWhere('id', $result->assessment_type_id);
                if ($assessmentType && $totalPercentage > 0) {
                    $weight = $assessmentType->percentage / $totalPercentage;
                    $subjectTotal += $result->score * $weight;
                } else {
                    $subjectTotal += $result->score;
                }
            }

            $subjectTotal = round($subjectTotal, 2);
            $subjectScores[$subjectId] = $subjectTotal;
            $totalScore += $subjectTotal;

            if ($subjectTotal >= $this->passMark) {
                $subjectsPassed++;
            } else {
                $subjectsFailed++;
            }
        }

        $totalSubjects = count($subjectScores);
        $averageScore = $totalSubjects > 0 ? round($totalScore / $totalSubjects, 2) : 0;
        $overallGrade = $this->getGrade($averageScore, $gradingRules);
        $overallRemark = $this->getRemark($averageScore, $gradingRules);

        $teacherComment = $this->getAutoTeacherComment($averageScore);
        $principalComment = $this->getAutoPrincipalComment($averageScore);

        $attendancePercentage = $attendanceMap[$studentId] ?? null;

        $reportCard = StudentReportCard::updateOrCreate(
            [
                'student_id' => $studentId,
                'exam_id' => $exam->id,
            ],
            [
                'school_id' => $schoolClass->school_id,
                'school_class_id' => $schoolClass->id,
                'total_score' => $totalScore,
                'average_score' => $averageScore,
                'overall_grade' => $overallGrade,
                'overall_remark' => $overallRemark,
                'class_position' => null,
                'total_subjects' => $totalSubjects,
                'subjects_passed' => $subjectsPassed,
                'subjects_failed' => $subjectsFailed,
                'attendance_percentage' => $attendancePercentage,
                'teacher_comment' => $teacherComment,
                'principal_comment' => $principalComment,
                'status' => 'draft',
            ]
        );

        return [
            'report_card' => $reportCard,
            'average_score' => $averageScore,
            'subject_scores' => $subjectScores,
        ];
    }

    private function getGrade(float $score, $gradingRules): ?string
    {
        foreach ($gradingRules as $rule) {
            if ($score >= $rule->min_score && $score <= $rule->max_score) {
                return $rule->grade;
            }
        }

        return null;
    }

    private function getRemark(float $score, $gradingRules): ?string
    {
        foreach ($gradingRules as $rule) {
            if ($score >= $rule->min_score && $score <= $rule->max_score) {
                return $rule->remark;
            }
        }

        return null;
    }

    private function batchAttendancePercentages(array $studentIds, int $schoolClassId, Exam $exam): array
    {
        if (empty($studentIds)) {
            return [];
        }

        $startDate = $exam->start_date ?? now()->subMonth();
        $endDate = $exam->end_date ?? now();

        $attendanceData = Attendance::where('school_class_id', $schoolClassId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->whereIn('student_id', $studentIds)
            ->selectRaw('student_id, count(*) as total_days, sum(case when status in ("present","late") then 1 else 0 end) as present_days')
            ->groupBy('student_id')
            ->get();

        $result = [];
        foreach ($attendanceData as $row) {
            $result[$row->student_id] = $row->total_days > 0
                ? round(($row->present_days / $row->total_days) * 100, 2)
                : null;
        }

        foreach ($studentIds as $studentId) {
            if (! isset($result[$studentId])) {
                $result[$studentId] = null;
            }
        }

        return $result;
    }

    private function getAutoTeacherComment(float $averageScore): string
    {
        if ($averageScore >= 80) {
            return 'Excellent performance. Keep it up!';
        } elseif ($averageScore >= 70) {
            return 'Very good performance. Keep it up!';
        } elseif ($averageScore >= 60) {
            return 'Good effort. There is room for improvement.';
        } elseif ($averageScore >= 50) {
            return 'Fair performance. Needs improvement.';
        } else {
            return 'Poor performance. Needs serious attention.';
        }
    }

    private function getAutoPrincipalComment(float $averageScore): string
    {
        if ($averageScore >= 80) {
            return 'Outstanding. Promoted.';
        } elseif ($averageScore >= 70) {
            return 'Very good. Promoted.';
        } elseif ($averageScore >= 60) {
            return 'Good. Promoted.';
        } elseif ($averageScore >= 50) {
            return 'Fair. Promoted on probation.';
        } else {
            return 'Needs to work harder next term.';
        }
    }

    private function calculatePositions(Exam $exam, SchoolClass $schoolClass, array $studentAverages): void
    {
        if (empty($studentAverages)) {
            return;
        }

        $sorted = $studentAverages;
        arsort($sorted);

        $position = 1;
        $rank = 1;
        $previousScore = null;
        $updates = [];

        foreach ($sorted as $studentId => $averageScore) {
            if ($previousScore !== null && $averageScore < $previousScore) {
                $rank = $position;
            }

            $updates[$studentId] = $rank;
            $position++;
            $previousScore = $averageScore;
        }

        foreach ($updates as $studentId => $rank) {
            StudentReportCard::where('exam_id', $exam->id)
                ->where('student_id', $studentId)
                ->where('school_class_id', $schoolClass->id)
                ->update(['class_position' => $rank]);
        }
    }

    public function getSubjectScores(Exam $exam, SchoolClass $schoolClass, int $studentId): array
    {
        $schoolId = $schoolClass->school_id;

        $assessmentTypes = AssessmentType::where('school_id', $schoolId)
            ->where('status', true)
            ->get();

        $totalPercentage = $assessmentTypes->sum('percentage');

        $results = StudentResult::where('exam_id', $exam->id)
            ->where('school_class_id', $schoolClass->id)
            ->where('student_id', $studentId)
            ->with('subject', 'assessmentType')
            ->get();

        $subjectGroups = $results->groupBy('subject_id');

        $subjectScores = [];

        foreach ($subjectGroups as $subjectId => $subjectResults) {
            $subjectTotal = 0;

            foreach ($subjectResults as $result) {
                $assessmentType = $assessmentTypes->firstWhere('id', $result->assessment_type_id);
                if ($assessmentType && $totalPercentage > 0) {
                    $weight = $assessmentType->percentage / $totalPercentage;
                    $subjectTotal += $result->score * $weight;
                } else {
                    $subjectTotal += $result->score;
                }
            }

            $subject = $subjectResults->first()->subject;

            $subjectScores[$subjectId] = [
                'subject' => $subject,
                'total_score' => round($subjectTotal, 2),
                'assessments' => $subjectResults->map(function ($result) {
                    return [
                        'assessment_type' => $result->assessmentType->name,
                        'score' => $result->score,
                        'percentage' => $result->assessmentType->percentage,
                    ];
                }),
            ];
        }

        return $subjectScores;
    }

    public function publishResults(Exam $exam, SchoolClass $schoolClass): int
    {
        return StudentReportCard::where('exam_id', $exam->id)
            ->where('school_class_id', $schoolClass->id)
            ->where('status', 'approved')
            ->update([
                'status' => 'published',
                'published_at' => now(),
            ]);
    }

    public function approveResults(Exam $exam, SchoolClass $schoolClass): int
    {
        return StudentReportCard::where('exam_id', $exam->id)
            ->where('school_class_id', $schoolClass->id)
            ->where('status', 'draft')
            ->update(['status' => 'approved']);
    }
}
