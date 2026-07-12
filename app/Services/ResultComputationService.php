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

        $studentAverages = [];

        DB::transaction(function () use ($exam, $schoolClass, $students, $resultsByStudent, $assessmentTypes, $gradingRules, &$studentAverages) {
            foreach ($resultsByStudent as $studentId => $studentResults) {
                $computed = $this->computeStudentResult(
                    $exam,
                    $schoolClass,
                    $studentId,
                    $studentResults,
                    $assessmentTypes,
                    $gradingRules
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
        $gradingRules
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

        $attendancePercentage = $this->getAttendancePercentage($studentId, $schoolClass->id, $exam);

        $position = $this->getStudentPosition($exam, $schoolClass, $averageScore, $studentAverages ?? []);

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
                'class_position' => $position,
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

    private function getAttendancePercentage(int $studentId, int $schoolClassId, Exam $exam): ?float
    {
        $startDate = $exam->start_date ?? now()->subMonth();
        $endDate = $exam->end_date ?? now();

        $totalDays = Attendance::where('student_id', $studentId)
            ->where('school_class_id', $schoolClassId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->count();

        if ($totalDays === 0) {
            return null;
        }

        $presentDays = Attendance::where('student_id', $studentId)
            ->where('school_class_id', $schoolClassId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->whereIn('status', ['present', 'late'])
            ->count();

        return round(($presentDays / $totalDays) * 100, 2);
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

    private function getStudentPosition(Exam $exam, SchoolClass $schoolClass, float $averageScore, array $studentAverages): int
    {
        $allAverages = StudentReportCard::where('exam_id', $exam->id)
            ->where('school_class_id', $schoolClass->id)
            ->pluck('average_score', 'student_id')
            ->toArray();

        $allAverages = array_merge($allAverages, $studentAverages);

        arsort($allAverages);

        $position = 1;
        $rank = 1;
        $previousScore = null;

        foreach ($allAverages as $score) {
            if ($previousScore !== null && $score < $previousScore) {
                $rank = $position;
            }

            if ($score === $averageScore) {
                return $rank;
            }

            $position++;
            $previousScore = $score;
        }

        return $position;
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

        foreach ($sorted as $studentId => $averageScore) {
            if ($previousScore !== null && $averageScore < $previousScore) {
                $rank = $position;
            }

            StudentReportCard::where('exam_id', $exam->id)
                ->where('student_id', $studentId)
                ->where('school_class_id', $schoolClass->id)
                ->update(['class_position' => $rank]);

            $position++;
            $previousScore = $averageScore;
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
