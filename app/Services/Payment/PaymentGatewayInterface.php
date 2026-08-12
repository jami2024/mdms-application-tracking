<?php

namespace App\Services\Payment;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    // Returns the URL the applicant should be redirected to in order to pay.
    public function initiate(Payment $payment): string;

    // Verifies an inbound callback/webhook payload actually belongs to and
    // confirms the given payment (signature/hash check in a real gateway).
    public function verify(Payment $payment, array $callbackData): bool;
}
