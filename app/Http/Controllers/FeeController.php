<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FeeController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────

    public function dashboard(): View
    {
        $totalCollected = FeePayment::sum('amount_paid');
        $totalOutstanding = FeePayment::selectRaw('
            SUM(fee_structures.amount) - COALESCE(SUM(fee_payments.amount_paid), 0) as total_outstanding
        ')
            ->join('fee_structures', 'fee_payments.fee_structure_id', '=', 'fee_structures.id')
            ->value('total_outstanding') ?? 0;

        $collectionToday = FeePayment::where('payment_date', now()->toDateString())->sum('amount_paid');
        $collectionThisMonth = FeePayment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount_paid');

        $recentPayments = FeePayment::with('student', 'feeStructure.schoolClass')
            ->latest('payment_date')
            ->latest('id')
            ->take(10)
            ->get();

        return view('fees.dashboard', compact(
            'totalCollected',
            'totalOutstanding',
            'collectionToday',
            'collectionThisMonth',
            'recentPayments',
        ));
    }

    // ── Fee Structures ─────────────────────────────────────

    public function feeStructureIndex(Request $request): View
    {
        $feeStructures = FeeStructure::query()
            ->with('schoolClass')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('term', 'like', "%{$search}%")
                    ->orWhere('session', 'like', "%{$search}%");
            })
            ->when($request->class_id, function ($query, $classId) {
                $query->where('school_class_id', $classId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $classes = SchoolClass::orderBy('name')->get();

        return view('fees.structures.index', compact('feeStructures', 'classes'));
    }

    public function feeStructureCreate(): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('fees.structures.create', compact('classes'));
    }

    public function feeStructureStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:999999999.99',
            'description' => 'nullable|string|max:1000',
            'term' => 'nullable|string|max:100',
            'session' => 'nullable|string|max:100',
            'due_date' => 'nullable|date',
        ]);

        $class = SchoolClass::findOrFail($validated['school_class_id']);
        $validated['school_id'] = $class->school_id;
        $validated['status'] = true;

        FeeStructure::create($validated);

        return redirect()
            ->route('fees.structures.index')
            ->with('success', 'Fee structure created successfully.');
    }

    public function feeStructureEdit(FeeStructure $feeStructure): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('fees.structures.edit', compact('feeStructure', 'classes'));
    }

    public function feeStructureUpdate(Request $request, FeeStructure $feeStructure): RedirectResponse
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:999999999.99',
            'description' => 'nullable|string|max:1000',
            'term' => 'nullable|string|max:100',
            'session' => 'nullable|string|max:100',
            'due_date' => 'nullable|date',
            'status' => 'required|boolean',
        ]);

        $class = SchoolClass::findOrFail($validated['school_class_id']);
        $validated['school_id'] = $class->school_id;

        $feeStructure->update($validated);

        return redirect()
            ->route('fees.structures.index')
            ->with('success', 'Fee structure updated successfully.');
    }

    public function feeStructureDestroy(FeeStructure $feeStructure): RedirectResponse
    {
        if ($feeStructure->payments()->exists()) {
            return back()->with('error', 'Cannot delete a fee structure that has payments recorded.');
        }

        $feeStructure->delete();

        return redirect()
            ->route('fees.structures.index')
            ->with('success', 'Fee structure deleted successfully.');
    }

    // ── Payments ───────────────────────────────────────────

    public function paymentIndex(Request $request): View
    {
        $payments = FeePayment::query()
            ->with('student', 'feeStructure.schoolClass')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('admission_number', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%");
            })
            ->when($request->class_id, function ($query, $classId) {
                $query->whereHas('feeStructure', function ($q) use ($classId) {
                    $q->where('school_class_id', $classId);
                });
            })
            ->when($request->method, function ($query, $method) {
                $query->where('payment_method', $method);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->where('payment_date', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->where('payment_date', '<=', $date);
            })
            ->latest('payment_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $classes = SchoolClass::orderBy('name')->get();

        return view('fees.payments.index', compact('payments', 'classes'));
    }

    public function paymentCreate(Request $request): View
    {
        $students = Student::with('schoolClass')->where('status', 'active')->orderBy('first_name')->get();
        $feeStructures = FeeStructure::with('schoolClass')->where('status', true)->orderBy('title')->get();

        $selectedStudent = $request->student_id ? Student::find($request->student_id) : null;
        $studentFeeStructures = $selectedStudent && $selectedStudent->school_class_id
            ? $feeStructures->where('school_class_id', $selectedStudent->school_class_id)
            : collect();

        $outstandingBalances = [];
        if ($selectedStudent) {
            foreach ($studentFeeStructures as $fs) {
                $totalPaid = FeePayment::where('student_id', $selectedStudent->id)
                    ->where('fee_structure_id', $fs->id)
                    ->sum('amount_paid');
                $outstandingBalances[$fs->id] = (float) $fs->amount - (float) $totalPaid;
            }
        }

        return view('fees.payments.create', compact(
            'students',
            'feeStructures',
            'selectedStudent',
            'studentFeeStructures',
            'outstandingBalances',
        ));
    }

    public function paymentStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'amount_paid' => 'required|numeric|min:0.01|max:999999999.99',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,transfer,card,other',
            'reference' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:500',
        ]);

        $student = Student::findOrFail($validated['student_id']);
        $feeStructure = FeeStructure::findOrFail($validated['fee_structure_id']);

        if ($student->school_id !== $feeStructure->school_id) {
            return back()
                ->withErrors(['student_id' => 'The selected student does not belong to the same school as the fee structure.'])
                ->withInput();
        }

        if ($student->school_class_id !== $feeStructure->school_class_id) {
            return back()
                ->withErrors(['fee_structure_id' => 'The selected fee structure does not belong to the student\'s class.'])
                ->withInput();
        }

        $totalPaid = FeePayment::where('student_id', $student->id)
            ->where('fee_structure_id', $feeStructure->id)
            ->sum('amount_paid');

        $outstanding = (float) $feeStructure->amount - (float) $totalPaid;

        if ($validated['amount_paid'] > $outstanding + 0.01) {
            return back()
                ->withErrors(['amount_paid' => "Amount paid ({$validated['amount_paid']}) exceeds outstanding balance of {$outstanding}."])
                ->withInput();
        }

        $validated['school_id'] = $student->school_id;
        $validated['recorded_by'] = auth()->id();

        DB::transaction(function () use ($validated) {
            FeePayment::create($validated);
        });

        return redirect()
            ->route('fees.payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function paymentShow(FeePayment $payment): View
    {
        $payment->load('student', 'feeStructure.schoolClass', 'school');

        return view('fees.payments.show', compact('payment'));
    }

    public function receipt(FeePayment $payment): View
    {
        $payment->load('student', 'feeStructure.schoolClass', 'school');

        $totalPaid = FeePayment::where('student_id', $payment->student_id)
            ->where('fee_structure_id', $payment->fee_structure_id)
            ->sum('amount_paid');

        $balance = (float) $payment->feeStructure->amount - (float) $totalPaid;

        return view('fees.payments.receipt', compact('payment', 'totalPaid', 'balance'));
    }

    // ── Student Finance ────────────────────────────────────

    public function studentFinance(Student $student): View
    {
        $student->load('school', 'schoolClass');

        $feeStructures = FeeStructure::where('school_class_id', $student->school_class_id)
            ->where('status', true)
            ->get();

        $financeData = $feeStructures->map(function ($fs) use ($student) {
            $totalPaid = FeePayment::where('student_id', $student->id)
                ->where('fee_structure_id', $fs->id)
                ->sum('amount_paid');

            return [
                'fee_structure' => $fs,
                'total_paid' => (float) $totalPaid,
                'balance' => (float) $fs->amount - (float) $totalPaid,
            ];
        });

        $totalFees = $financeData->sum('fee_structure.amount');
        $totalPaid = $financeData->sum('total_paid');
        $totalBalance = $financeData->sum('balance');

        $payments = FeePayment::with('feeStructure')
            ->where('student_id', $student->id)
            ->latest('payment_date')
            ->latest('id')
            ->paginate(15);

        return view('fees.students.finance', compact(
            'student',
            'financeData',
            'totalFees',
            'totalPaid',
            'totalBalance',
            'payments',
        ));
    }

    // ── Reports ────────────────────────────────────────────

    public function outstandingReport(Request $request): View
    {
        $classes = SchoolClass::orderBy('name')->get();
        $selectedClass = $request->class_id;

        $students = Student::with('schoolClass')
            ->where('status', 'active')
            ->when($selectedClass, function ($query) use ($selectedClass) {
                $query->where('school_class_id', $selectedClass);
            })
            ->orderBy('first_name')
            ->get();

        $outstandingStudents = $students->map(function ($student) {
            $feeStructures = FeeStructure::where('school_class_id', $student->school_class_id)
                ->where('status', true)
                ->get();

            $totalFees = $feeStructures->sum('amount');
            $totalPaid = FeePayment::where('student_id', $student->id)
                ->whereIn('fee_structure_id', $feeStructures->pluck('id'))
                ->sum('amount_paid');
            $balance = (float) $totalFees - (float) $totalPaid;

            return [
                'student' => $student,
                'total_fees' => (float) $totalFees,
                'total_paid' => (float) $totalPaid,
                'balance' => $balance,
            ];
        })->filter(function ($item) {
            return $item['balance'] > 0.01;
        })->values();

        $totalOutstanding = $outstandingStudents->sum('balance');

        return view('fees.reports.outstanding', compact('classes', 'selectedClass', 'outstandingStudents', 'totalOutstanding'));
    }

    public function paidReport(Request $request): View
    {
        $classes = SchoolClass::orderBy('name')->get();
        $selectedClass = $request->class_id;

        $students = Student::with('schoolClass')
            ->where('status', 'active')
            ->when($selectedClass, function ($query) use ($selectedClass) {
                $query->where('school_class_id', $selectedClass);
            })
            ->orderBy('first_name')
            ->get();

        $paidStudents = $students->filter(function ($student) {
            $feeStructures = FeeStructure::where('school_class_id', $student->school_class_id)
                ->where('status', true)
                ->get();

            if ($feeStructures->isEmpty()) {
                return false;
            }

            $totalPaid = FeePayment::where('student_id', $student->id)
                ->whereIn('fee_structure_id', $feeStructures->pluck('id'))
                ->sum('amount_paid');

            return (float) $totalPaid >= (float) $feeStructures->sum('amount');
        })->values();

        return view('fees.reports.paid', compact('classes', 'selectedClass', 'paidStudents'));
    }

    public function classSummaryReport(Request $request): View
    {
        $classes = SchoolClass::withCount(['students' => function ($query) {
            $query->where('status', 'active');
        }])->orderBy('name')->get();

        $summaries = $classes->map(function ($class) {
            $feeStructures = FeeStructure::where('school_class_id', $class->id)
                ->where('status', true)
                ->get();

            $totalExpected = $feeStructures->sum('amount') * $class->students_count;
            $totalCollected = FeePayment::whereHas('feeStructure', function ($q) use ($class) {
                $q->where('school_class_id', $class->id);
            })->sum('amount_paid');

            $studentIds = $class->students()->where('status', 'active')->pluck('id');
            $studentsWithFullPayment = 0;

            foreach ($studentIds as $studentId) {
                $studentPaid = FeePayment::where('student_id', $studentId)
                    ->whereHas('feeStructure', function ($q) use ($class) {
                        $q->where('school_class_id', $class->id);
                    })
                    ->sum('amount_paid');

                if ((float) $studentPaid >= (float) $feeStructures->sum('amount')) {
                    $studentsWithFullPayment++;
                }
            }

            return [
                'class' => $class,
                'total_expected' => (float) $totalExpected,
                'total_collected' => (float) $totalCollected,
                'outstanding' => (float) $totalExpected - (float) $totalCollected,
                'collection_rate' => $totalExpected > 0 ? round(($totalCollected / $totalExpected) * 100, 1) : 0,
                'students_fully_paid' => $studentsWithFullPayment,
                'students_with_balance' => $class->students_count - $studentsWithFullPayment,
            ];
        });

        return view('fees.reports.class-summary', compact('summaries'));
    }

    public function dailyCollectionsReport(Request $request): View
    {
        $date = $request->date ?? now()->toDateString();

        $payments = FeePayment::with('student', 'feeStructure.schoolClass')
            ->where('payment_date', $date)
            ->latest('id')
            ->get();

        $totalCollected = $payments->sum('amount_paid');
        $paymentCount = $payments->count();

        $byMethod = $payments->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount_paid'),
            ];
        });

        return view('fees.reports.daily-collections', compact('date', 'payments', 'totalCollected', 'paymentCount', 'byMethod'));
    }

    public function monthlyCollectionsReport(Request $request): View
    {
        $month = $request->month ?? now()->format('Y-m');
        $startDate = now()->parse($month)->startOfMonth()->toDateString();
        $endDate = now()->parse($month)->endOfMonth()->toDateString();

        $payments = FeePayment::with('student', 'feeStructure.schoolClass')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->latest('payment_date')
            ->latest('id')
            ->get();

        $totalCollected = $payments->sum('amount_paid');
        $paymentCount = $payments->count();

        $byDay = $payments->groupBy(function ($p) {
            return $p->payment_date->format('Y-m-d');
        })->map(function ($dayPayments) {
            return [
                'count' => $dayPayments->count(),
                'total' => $dayPayments->sum('amount_paid'),
            ];
        })->sortKeys();

        $byMethod = $payments->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount_paid'),
            ];
        });

        return view('fees.reports.monthly-collections', compact(
            'month',
            'startDate',
            'endDate',
            'payments',
            'totalCollected',
            'paymentCount',
            'byDay',
            'byMethod',
        ));
    }
}
