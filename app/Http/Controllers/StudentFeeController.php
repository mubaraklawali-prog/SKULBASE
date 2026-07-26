<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesStudent;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use Illuminate\View\View;

class StudentFeeController extends Controller
{
    use ResolvesStudent;

    public function index(): View
    {
        $student = $this->resolveStudent();

        $feeStructures = FeeStructure::where('school_class_id', $student->school_class_id)
            ->where('school_id', $student->school_id)
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $payments = FeePayment::where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->with('feeStructure')
            ->latest('payment_date')
            ->latest('id')
            ->get();

        $paymentsByFee = $payments->groupBy('fee_structure_id');

        $feeStructures->each(function ($fee) use ($paymentsByFee) {
            $paid = $paymentsByFee->has($fee->id)
                ? (float) $paymentsByFee[$fee->id]->sum('amount_paid')
                : 0;

            $fee->computed_status = $paid >= $fee->amount
                ? 'paid'
                : ($paid > 0 ? 'partial' : 'unpaid');
        });

        $summary = [
            'total_fees' => (float) $feeStructures->sum('amount'),
            'total_paid' => (float) $payments->sum('amount_paid'),
            'balance' => max(0, (float) $feeStructures->sum('amount') - (float) $payments->sum('amount_paid')),
        ];

        return view('student.fees.index', compact(
            'student',
            'payments',
            'feeStructures',
            'summary',
        ));
    }
}
