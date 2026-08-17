<?php

namespace App\Services;

use App\Models\PaymentTransaction;
use App\Models\School;
use App\Models\Subscription;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class PaystackService
{
    private string $secretKey;

    private string $baseUrl;

    private string $webhookSecret;

    public function __construct()
    {
        $this->secretKey = (string) config('services.paystack.secret_key');
        $this->baseUrl = (string) config('services.paystack.base_url', 'https://api.paystack.co');
        $this->webhookSecret = (string) config('services.paystack.webhook_secret');
    }

    /**
     * Initialize a Paystack transaction and return the authorization URL.
     */
    public function initializeTransaction(
        School $school,
        float $amount,
        string $email,
        string $reference,
        ?string $callbackUrl = null,
    ): array {
        $this->ensureConfigured();

        try {
            $response = $this->client()->post('/transaction/initialize', [
                'amount' => PaymentTransaction::toPaystackAmount($amount),
                'email' => $email,
                'reference' => $reference,
                'currency' => 'NGN',
                'metadata' => [
                    'school_id' => $school->id,
                    'school_name' => $school->name,
                ],
                ...($callbackUrl ? ['callback_url' => $callbackUrl] : []),
            ]);
        } catch (ConnectionException $e) {
            throw new InvalidArgumentException(
                'Paystack connection failed: '.$e->getMessage()
            );
        }

        if (! $response->successful()) {
            throw new InvalidArgumentException(
                'Paystack initialization failed: '.$response->json('message', 'Unknown error')
            );
        }

        return [
            'authorization_url' => $response->json('data.authorization_url'),
            'access_code' => $response->json('data.access_code'),
            'reference' => $response->json('data.reference'),
        ];
    }

    /**
     * Verify a Paystack transaction by reference.
     *
     * @return array{status: string, amount: float, reference: string, gateway_response: array}
     */
    public function verifyTransaction(string $reference): array
    {
        $this->ensureConfigured();

        try {
            $response = $this->client()->get("/transaction/verify/{$reference}");
        } catch (ConnectionException $e) {
            throw new InvalidArgumentException(
                'Paystack verification connection failed: '.$e->getMessage()
            );
        }

        if (! $response->successful()) {
            throw new InvalidArgumentException(
                'Paystack verification failed: '.$response->json('message', 'Unknown error')
            );
        }

        $data = $response->json('data');

        return [
            'status' => $data['status'],
            'amount' => PaymentTransaction::fromPaystackAmount($data['amount']),
            'reference' => $data['reference'],
            'gateway_response' => $data,
        ];
    }

    /**
     * Generate a unique payment reference for a school.
     */
    public function generateReference(School $school): string
    {
        return 'SB-'.$school->id.'-'.time().'-'.strtoupper(bin2hex(random_bytes(4)));
    }

    /**
     * Create a pending payment transaction record.
     */
    public function createTransaction(
        School $school,
        float $amount,
        string $reference,
        ?Subscription $subscription = null,
    ): PaymentTransaction {
        return PaymentTransaction::create([
            'school_id' => $school->id,
            'subscription_id' => $subscription?->id,
            'amount' => $amount,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'reference' => $reference,
            'status' => 'pending',
        ]);
    }

    /**
     * Mark a transaction as successful.
     */
    public function markSuccess(PaymentTransaction $transaction, array $gatewayResponse = []): PaymentTransaction
    {
        $transaction->update([
            'status' => 'success',
            'gateway_response' => $gatewayResponse,
        ]);

        return $transaction->fresh();
    }

    /**
     * Mark a transaction as failed.
     */
    public function markFailed(PaymentTransaction $transaction, array $gatewayResponse = []): PaymentTransaction
    {
        $transaction->update([
            'status' => 'failed',
            'gateway_response' => $gatewayResponse,
        ]);

        return $transaction->fresh();
    }

    /**
     * Mark a transaction as abandoned.
     */
    public function markAbandoned(PaymentTransaction $transaction): PaymentTransaction
    {
        $transaction->update([
            'status' => 'abandoned',
        ]);

        return $transaction->fresh();
    }

    /**
     * Verify a Paystack webhook signature.
     *
     * @see https://paystack.com/docs/payments/webhooks/
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $secret = $this->webhookSecret;

        if (blank($secret)) {
            return false;
        }

        $hash = hash_hmac('sha512', $payload, $secret);

        return hash_equals($hash, $signature);
    }

    /**
     * Check if the Paystack service is configured with valid keys.
     */
    public function isConfigured(): bool
    {
        return filled(config('services.paystack.secret_key'))
            && filled(config('services.paystack.public_key'));
    }

    private function client(): PendingRequest
    {
        return Http::withToken($this->secretKey)
            ->baseUrl($this->baseUrl)
            ->timeout(30)
            ->accept('application/json');
    }

    private function ensureConfigured(): void
    {
        if (blank($this->secretKey)) {
            throw new InvalidArgumentException(
                'Paystack secret key is not configured. Set PAYSTACK_SECRET_KEY in your .env file.'
            );
        }
    }
}
