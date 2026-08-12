<?php

namespace App\Services\Payment;

use App\Models\Payment;

// Local sandbox gateway used when config('services.payment.driver') === 'mock'
// (the default). Sends the applicant to our own /payments/{payment}/sandbox
// page instead of a real gateway checkout, so the full pay -> webhook ->
// mark-paid -> unlock-certificate flow can be exercised without real
// SSLCOMMERZ/bKash credentials or outbound network access.
class MockGateway implements PaymentGatewayInterface
{
    public function initiate(Payment $payment): string
    {
        return route('payments.sandbox', $payment);
    }

    public function verify(Payment $payment, array $callbackData): bool
    {
        return ($callbackData['reference'] ?? null) === $payment->reference;
    }
}
