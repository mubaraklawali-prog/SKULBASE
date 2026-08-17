<?php

namespace App\Services;

use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class PaymentService
{
    public function __construct(
        private PaystackService $paystackService,
        private SubscriptionService $subscriptionService,
    ) {}

    /**
     * Process a verified payment: mark transaction success, activate/renew subscription.
     *
     * This is the single entry point used by both the webhook and the callback.
     * It is idempotent — calling it on an already-successful transaction is a safe no-op.
     *
     * @return array{transaction: PaymentTransaction, subscription: Subscription, action: string}
     */
    public function processSuccessfulPayment(PaymentTransaction $transaction): array
    {
        if ($transaction->isSuccess()) {
            return [
                'transaction' => $transaction,
                'subscription' => $transaction->subscription,
                'action' => 'already_processed',
            ];
        }

        if (! $transaction->isPending()) {
            Log::warning('PaymentService: attempted to process non-pending transaction', [
                'transaction_id' => $transaction->id,
                'reference' => $transaction->reference,
                'status' => $transaction->status,
            ]);

            return [
                'transaction' => $transaction,
                'subscription' => null,
                'action' => 'skipped',
            ];
        }

        $school = $transaction->school;

        $notes = is_string($transaction->notes)
            ? json_decode($transaction->notes, true)
            : ($transaction->notes ?? []);

        $planId = $notes['plan_id'] ?? null;
        $billingCycle = $notes['billing_cycle'] ?? 'monthly';

        $plan = $planId ? Plan::find($planId) : null;
        if (! $plan) {
            $plan = $school->selectedPlan ?? Plan::active()->first();
        }

        if (! $plan) {
            throw new InvalidArgumentException('No valid plan found for this payment.');
        }

        $transactionAmount = (float) $transaction->amount;

        $existingSubscription = $this->subscriptionService->getActiveSubscription($school);

        $isNewActivation = ! $existingSubscription
            || $existingSubscription->plan_id !== $plan->id
            || $existingSubscription->isExpired();

        return DB::transaction(function () use (
            $transaction,
            $school,
            $plan,
            $billingCycle,
            $transactionAmount,
            $existingSubscription,
            $isNewActivation,
        ) {
            $this->paystackService->markSuccess($transaction, [
                'processed_at' => now()->toIso8601String(),
            ]);

            if ($isNewActivation) {
                $subscription = $this->activateSubscription($school, $plan, $billingCycle, $transaction);
            } else {
                $subscription = $this->renewSubscription($existingSubscription, $transactionAmount);
            }

            $transaction->update(['subscription_id' => $subscription->id]);

            $this->subscriptionService->affiliateService->handleSubscriptionPayment($subscription);

            $action = $isNewActivation ? 'activated' : 'renewed';

            Log::info('PaymentService: payment processed successfully', [
                'transaction_id' => $transaction->id,
                'reference' => $transaction->reference,
                'subscription_id' => $subscription->id,
                'action' => $action,
            ]);

            return [
                'transaction' => $transaction->fresh(),
                'subscription' => $subscription,
                'action' => $action,
            ];
        });
    }

    /**
     * Verify a transaction with Paystack and process it if valid.
     *
     * @throws InvalidArgumentException if verification fails
     */
    public function verifyAndProcess(PaymentTransaction $transaction): array
    {
        $paystackData = $this->paystackService->verifyTransaction($transaction->reference);

        $this->validatePaystackResponse($transaction, $paystackData);

        return $this->processSuccessfulPayment($transaction);
    }

    private function validatePaystackResponse(PaymentTransaction $transaction, array $paystackData): void
    {
        if ($paystackData['status'] !== 'success') {
            $this->paystackService->markFailed($transaction, $paystackData['gateway_response']);

            throw new InvalidArgumentException(
                'Paystack verification shows payment was not successful: '.$paystackData['status']
            );
        }

        if ($paystackData['reference'] !== $transaction->reference) {
            throw new InvalidArgumentException(
                'Reference mismatch: expected '.$transaction->reference.', got '.$paystackData['reference']
            );
        }

        $expectedAmount = (float) $transaction->amount;
        $paystackAmount = $paystackData['amount'];

        if (abs($expectedAmount - $paystackAmount) > 0.01) {
            $this->paystackService->markFailed($transaction, [
                ...$paystackData['gateway_response'],
                'amount_mismatch' => true,
                'expected' => $expectedAmount,
                'received' => $paystackAmount,
            ]);

            throw new InvalidArgumentException(
                "Amount mismatch: expected ₦{$expectedAmount}, got ₦{$paystackAmount}"
            );
        }
    }

    private function activateSubscription(
        School $school,
        Plan $plan,
        string $billingCycle,
        PaymentTransaction $transaction,
    ): Subscription {
        $this->subscriptionService->deactivateExisting($school);

        $expiresAt = $billingCycle === 'yearly'
            ? now()->copy()->addYear()
            : now()->copy()->addMonth();

        $subscription = Subscription::create([
            'school_id' => $school->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $billingCycle,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => $expiresAt,
            'trial_starts_at' => null,
            'trial_ends_at' => null,
            'grace_ends_at' => null,
            'cancelled_at' => null,
            'is_trial' => false,
            'amount_paid' => $plan->discountedPrice($billingCycle),
            'payment_reference' => $transaction->reference,
            'notes' => "Activated via payment {$transaction->reference}",
        ]);

        return $subscription;
    }

    private function renewSubscription(
        Subscription $subscription,
        float $transactionAmount,
    ): Subscription {
        $plan = $subscription->plan;
        $billingCycle = $subscription->billing_cycle;

        $expiresAt = $billingCycle === 'yearly'
            ? now()->copy()->addYear()
            : now()->copy()->addMonth();

        $subscription->update([
            'status' => 'active',
            'is_trial' => false,
            'starts_at' => now(),
            'expires_at' => $expiresAt,
            'grace_ends_at' => null,
            'cancelled_at' => null,
            'amount_paid' => $plan->discountedPrice($billingCycle),
        ]);

        return $subscription->fresh();
    }
}
