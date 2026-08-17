<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Services\PaymentService;
use App\Services\PaystackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private PaystackService $paystackService,
        private PaymentService $paymentService,
    ) {}

    public function checkout(Request $request): View|RedirectResponse
    {
        $school = Auth::user()->school;

        if (! $school) {
            abort(403, 'No school is associated with your account.');
        }

        $validated = $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);

        if (! $plan->is_active) {
            return back()->withErrors(['plan_id' => 'The selected plan is no longer available.'])->withInput();
        }

        if ((float) $plan->monthly_price <= 0 && (float) $plan->yearly_price <= 0) {
            return back()->withErrors(['plan_id' => 'Free Trial cannot be purchased through paid checkout.'])->withInput();
        }

        $billingCycle = $validated['billing_cycle'];
        $basePrice = $billingCycle === 'yearly'
            ? (float) $plan->yearly_price
            : (float) $plan->monthly_price;

        $finalPrice = $plan->discountedPrice($billingCycle);
        $hasDiscount = $plan->isDiscountActive() && $finalPrice < $basePrice;
        $discountAmount = $hasDiscount ? $basePrice - $finalPrice : 0;

        return view('school.subscription.checkout', compact(
            'school',
            'plan',
            'billingCycle',
            'basePrice',
            'finalPrice',
            'hasDiscount',
            'discountAmount',
        ));
    }

    public function pay(Request $request): RedirectResponse
    {
        $school = Auth::user()->school;

        if (! $school) {
            abort(403, 'No school is associated with your account.');
        }

        $validated = $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);

        if (! $plan->is_active) {
            return back()->withErrors(['plan_id' => 'The selected plan is no longer available.'])->withInput();
        }

        if ((float) $plan->monthly_price <= 0 && (float) $plan->yearly_price <= 0) {
            return back()->withErrors(['plan_id' => 'Free Trial cannot be purchased through paid checkout.'])->withInput();
        }

        $billingCycle = $validated['billing_cycle'];
        $amount = $plan->discountedPrice($billingCycle);

        if ($amount <= 0) {
            return back()->withErrors(['plan_id' => 'Invalid amount for the selected plan.'])->withInput();
        }

        $reference = $this->paystackService->generateReference($school);

        $transaction = $this->paystackService->createTransaction(
            $school,
            $amount,
            $reference,
        );

        try {
            $result = $this->paystackService->initializeTransaction(
                $school,
                $amount,
                $school->email,
                $reference,
                route('school.subscription.checkout.callback'),
            );

            $transaction->update([
                'notes' => json_encode([
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'billing_cycle' => $billingCycle,
                ]),
            ]);

            return redirect($result['authorization_url']);
        } catch (\InvalidArgumentException $e) {
            $this->paystackService->markFailed($transaction, [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'paystack' => 'Payment initialization failed: '.$e->getMessage(),
            ])->withInput();
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->query('reference', '');

        if (blank($reference)) {
            return redirect()->route('school.subscription.index')
                ->with('error', 'No payment reference provided.');
        }

        $transaction = PaymentTransaction::where('reference', $reference)->first();

        if (! $transaction) {
            Log::warning('CheckoutController: callback with unknown reference', ['reference' => $reference]);

            return redirect()->route('school.subscription.index')
                ->with('error', 'Payment not found. Please contact support.');
        }

        $user = Auth::user();

        if (! $user || $transaction->school_id !== $user->school_id) {
            abort(403, 'This payment does not belong to your school.');
        }

        if ($transaction->isSuccess()) {
            return redirect()->route('school.subscription.index')
                ->with('success', 'Payment already processed successfully.');
        }

        try {
            $result = $this->paymentService->verifyAndProcess($transaction);

            $message = match ($result['action']) {
                'activated' => 'Payment verified! Your subscription has been activated.',
                'renewed' => 'Payment verified! Your subscription has been renewed.',
                'already_processed' => 'Payment was already processed successfully.',
                default => 'Payment verified successfully.',
            };

            return redirect()->route('school.subscription.index')
                ->with('success', $message);
        } catch (\InvalidArgumentException $e) {
            Log::warning('CheckoutController: callback verification failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('school.subscription.index')
                ->with('error', 'Payment verification failed: '.$e->getMessage());
        }
    }
}
