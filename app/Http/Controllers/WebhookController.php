<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Services\PaymentService;
use App\Services\PaystackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private PaystackService $paystackService,
        private PaymentService $paymentService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature', '');

        if (! $this->paystackService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('WebhookController: invalid webhook signature');

            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $event = json_decode($payload, true);

        if (! is_array($event)) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $eventType = $event['event'] ?? '';
        $data = $event['data'] ?? [];

        Log::info('WebhookController: event received', [
            'event' => $eventType,
            'reference' => $data['reference'] ?? null,
        ]);

        if ($eventType !== 'charge.success') {
            return response()->json(['message' => 'Event ignored'], 200);
        }

        $reference = $data['reference'] ?? '';

        if (blank($reference)) {
            Log::warning('WebhookController: charge.success without reference');

            return response()->json(['message' => 'Missing reference'], 200);
        }

        $transaction = PaymentTransaction::where('reference', $reference)->first();

        if (! $transaction) {
            Log::warning('WebhookController: unknown reference', ['reference' => $reference]);

            return response()->json(['message' => 'Unknown reference'], 200);
        }

        try {
            $this->paymentService->verifyAndProcess($transaction);
        } catch (\InvalidArgumentException $e) {
            Log::warning('WebhookController: verification failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Verification failed'], 200);
        }

        return response()->json(['message' => 'Processed'], 200);
    }
}
