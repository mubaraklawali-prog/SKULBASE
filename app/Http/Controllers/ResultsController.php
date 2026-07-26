<?php

namespace App\Http\Controllers;

use App\Models\AssessmentType;
use App\Models\Exam;
use App\Models\GradingSystem;
use Illuminate\View\View;

class ResultsController extends Controller
{
    private function schoolId(): ?int
    {
        $user = auth()->user();

        return $user->role === 'super_admin' ? null : $user->school_id;
    }

    public function dashboard(): View
    {
        $schoolId = $this->schoolId();

        $scope = fn ($query) => $schoolId ? $query->where('school_id', $schoolId) : $query;

        $totalExams = $scope(Exam::query())->count();
        $totalAssessmentTypes = $scope(AssessmentType::query())->count();
        $totalGradingRules = $scope(GradingSystem::query())->count();

        $activeExams = $scope(Exam::where('status', true))->count();
        $activeAssessmentTypes = $scope(AssessmentType::where('status', true))->count();
        $totalPercentage = $scope(AssessmentType::where('status', true))->sum('percentage');

        $recentExams = $scope(Exam::query())->latest()->take(5)->get();

        return view('results.dashboard', compact(
            'totalExams',
            'totalAssessmentTypes',
            'totalGradingRules',
            'activeExams',
            'activeAssessmentTypes',
            'totalPercentage',
            'recentExams',
        ));
    }
}
