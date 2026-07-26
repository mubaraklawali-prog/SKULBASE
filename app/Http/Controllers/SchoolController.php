<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    public function index(Request $request): View
    {
        $schools = School::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('schools.index', compact('schools'));
    }

    public function create(): View
    {
        return view('schools.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:schools,slug',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $school = School::create($validated);

        $this->subscriptionService->createTrial($school);

        return redirect()
            ->route('schools.index')
            ->with('success', 'School created successfully with a 30-day free trial.');
    }

    public function show(School $school): View
    {
        $school->loadCount(['students', 'teachers', 'schoolClasses', 'subjects']);

        $recentStudents = $school->students()
            ->latest()
            ->limit(5)
            ->get();

        $recentTeachers = $school->teachers()
            ->latest()
            ->limit(5)
            ->get();

        $subscription = $school->activeSubscription;
        $schoolSetting = $school->setting;

        return view('schools.show', compact(
            'school',
            'recentStudents',
            'recentTeachers',
            'subscription',
            'schoolSetting',
        ));
    }

    public function edit(School $school): View
    {
        return view('schools.edit', compact('school'));
    }

    public function update(Request $request, School $school): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:schools,slug,'.$school->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $school->update($validated);

        return redirect()
            ->route('schools.index')
            ->with('success', 'School updated successfully.');
    }

    public function destroy(School $school): RedirectResponse
    {
        $hasData = $school->students()->exists()
            || $school->teachers()->exists()
            || $school->schoolClasses()->exists()
            || $school->subjects()->exists()
            || $school->exams()->exists()
            || $school->feeStructures()->exists()
            || $school->feePayments()->exists()
            || $school->assignments()->exists()
            || $school->announcements()->exists()
            || $school->timetables()->exists()
            || $school->subscriptions()->whereIn('status', ['trial', 'active', 'grace'])->exists();

        if ($hasData) {
            return back()->with('error', 'Cannot delete this school because it still has associated records (students, teachers, classes, or other data). Please remove all dependent records first.');
        }

        $school->users()->update(['school_id' => null]);
        $school->setting()->delete();
        $school->delete();

        return redirect()
            ->route('schools.index')
            ->with('success', 'School deleted successfully.');
    }

    public function toggleStatus(School $school): RedirectResponse
    {
        $school->update([
            'is_active' => ! $school->is_active,
        ]);

        $status = $school->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('schools.index')
            ->with('success', "School {$status} successfully.");
    }
}
