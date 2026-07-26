<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdmissionRequest;
use App\Http\Requests\UpdateAdmissionRequest;
use App\Models\Admission;
use App\Models\ParentModel;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $schoolId = $user->school_id;

        $query = Admission::with(['schoolClass'])
            ->where('school_id', $schoolId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('parent_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $admissions = $query->latest()->paginate(15)->withQueryString();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        return view('admissions.index', compact('admissions', 'classes'));
    }

    public function show(Admission $admission): View
    {
        $this->authorizeAdmission($admission);

        $admission->load(['schoolClass', 'school']);

        return view('admissions.show', compact('admission'));
    }

    public function edit(Admission $admission): View
    {
        $this->authorizeManage($admission);

        $classes = SchoolClass::where('school_id', $admission->school_id)
            ->orderBy('name')
            ->get();

        return view('admissions.edit', compact('admission', 'classes'));
    }

    public function update(UpdateAdmissionRequest $request, Admission $admission): RedirectResponse
    {
        $this->authorizeManage($admission);

        $data = $request->validated();

        if ($request->hasFile('passport')) {
            if ($admission->passport) {
                Storage::disk('public')->delete($admission->passport);
            }
            $data['passport'] = $request->file('passport')->store('admissions', 'public');
        }

        $admission->update($data);

        return redirect()->route('admissions.show', $admission)
            ->with('success', 'Admission updated successfully.');
    }

    public function destroy(Admission $admission): RedirectResponse
    {
        $this->authorizeManage($admission);

        if ($admission->passport) {
            Storage::disk('public')->delete($admission->passport);
        }

        $admission->delete();

        return redirect()->route('admissions.index')
            ->with('success', 'Admission deleted successfully.');
    }

    public function approve(Admission $admission): RedirectResponse
    {
        $this->authorizeManage($admission);

        if ($admission->status === 'approved') {
            return redirect()->route('admissions.show', $admission)
                ->with('error', 'This admission has already been approved.');
        }

        DB::transaction(function () use ($admission) {
            $nameParts = explode(' ', trim($admission->full_name), 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $student = Student::create([
                'school_id' => $admission->school_id,
                'admission_number' => $this->generateStudentAdmissionNumber($admission->school_id),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => $admission->gender,
                'date_of_birth' => $admission->date_of_birth,
                'email' => $admission->parent_email,
                'phone' => $admission->parent_phone,
                'address' => $admission->address,
                'school_class_id' => $admission->class_id,
                'status' => 'active',
            ]);

            $parentNameParts = explode(' ', trim($admission->parent_name), 2);
            $parentFirstName = $parentNameParts[0];
            $parentLastName = $parentNameParts[1] ?? '';

            $existingParent = null;
            if ($admission->parent_email || $admission->parent_phone) {
                $existingParent = ParentModel::where('school_id', $admission->school_id)
                    ->where(function ($query) use ($admission) {
                        if ($admission->parent_email) {
                            $query->where('email', $admission->parent_email);
                        }
                        if ($admission->parent_phone) {
                            $query->orWhere('phone', $admission->parent_phone);
                        }
                    })
                    ->first();
            }

            if ($existingParent) {
                $parent = $existingParent;
            } else {
                $parent = ParentModel::create([
                    'school_id' => $admission->school_id,
                    'first_name' => $parentFirstName,
                    'last_name' => $parentLastName,
                    'email' => $admission->parent_email,
                    'phone' => $admission->parent_phone,
                    'address' => $admission->address,
                    'status' => true,
                ]);
            }

            $student->parents()->attach($parent->id);

            $admission->update(['status' => 'approved']);
        });

        return redirect()->route('admissions.show', $admission)
            ->with('success', 'Admission approved. Student record and parent account created successfully.');
    }

    public function reject(Admission $admission): RedirectResponse
    {
        $this->authorizeManage($admission);

        $admission->update(['status' => 'rejected']);

        return redirect()->route('admissions.show', $admission)
            ->with('success', 'Admission rejected.');
    }

    // Public admission form
    public function form(): View
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admissions.form', compact('schools'));
    }

    public function submit(StoreAdmissionRequest $request): RedirectResponse
    {
        $school = School::findOrFail($request->school_id);

        $classes = SchoolClass::where('school_id', $school->id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $data = $request->validated();
        $data['application_number'] = Admission::generateApplicationNumber($school->id);
        $data['status'] = 'pending';

        if ($request->hasFile('passport')) {
            $data['passport'] = $request->file('passport')->store('admissions', 'public');
        }

        Admission::create($data);

        return redirect()->route('admissions.form')
            ->with('success', 'Application submitted successfully! Your application number is '.$data['application_number']);
    }

    protected function authorizeAdmission(Admission $admission): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_if($admission->school_id !== $user->school_id, 403, 'Unauthorized access.');
    }

    protected function authorizeManage(Admission $admission): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_if($admission->school_id !== $user->school_id, 403, 'Unauthorized access.');

        abort_if(! in_array($user->role, ['super_admin', 'school_admin']), 403, 'You do not have permission to manage admissions.');
    }

    private function generateStudentAdmissionNumber(int $schoolId): string
    {
        $prefix = 'STU-'.str_pad($schoolId, 3, '0', STR_PAD_LEFT);
        $date = now()->format('Ymd');
        $lastStudent = Student::where('admission_number', 'like', "{$prefix}-{$date}-%")
            ->latest('id')
            ->value('admission_number');

        if ($lastStudent) {
            $sequence = (int) substr($lastStudent, -4) + 1;
        } else {
            $sequence = 1;
        }

        return "{$prefix}-{$date}-".str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
