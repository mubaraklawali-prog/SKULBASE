<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesParentChildren;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentFeesController extends Controller
{
    use ResolvesParentChildren;

    public function index(Request $request): View
    {
        $children = $this->resolveParentChildren();
        $selectedStudentId = $request->student_id;
        $selectedStudent = $this->resolveSelectedChild($children, $selectedStudentId);

        $payments = collect();
        $feeStructures = collect();
        $summary = [
            'total_fees' => 0,
            'total_paid' => 0,
            'balance' => 0,
        ];

        if ($selectedStudent) {
            $feeStructures = FeeStructure::where('school_class_id', $selectedStudent->school_class_id)
                ->where('school_id', $selectedStudent->school_id)
                ->where('status', true)
                ->orderBy('created_at', 'desc')
                ->get();

            $payments = FeePayment::where('student_id', $selectedStudent->id)
                ->where('school_id', $selectedStudent->school_id)
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

            $summary['total_fees'] = (float) $feeStructures->sum('amount');
            $summary['total_paid'] = (float) $payments->sum('amount_paid');
            $summary['balance'] = max(0, $summary['total_fees'] - $summary['total_paid']);
        }

        return view('parent.fees.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'payments',
            'feeStructures',
            'summary',
        ));
    }
}
