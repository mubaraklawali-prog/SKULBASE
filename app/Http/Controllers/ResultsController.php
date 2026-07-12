<?php

namespace App\Http\Controllers;

use App\Models\AssessmentType;
use App\Models\Exam;
use App\Models\GradingSystem;
use Illuminate\View\View;

class ResultsController extends Controller
{
    public function dashboard(): View
    {
        $totalExams = Exam::count();
        $totalAssessmentTypes = AssessmentType::count();
        $totalGradingRules = GradingSystem::count();

        $activeExams = Exam::where('status', true)->count();
        $activeAssessmentTypes = AssessmentType::where('status', true)->count();
        $totalPercentage = AssessmentType::where('status', true)->sum('percentage');

        $recentExams = Exam::latest()->take(5)->get();

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
