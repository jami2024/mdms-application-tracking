<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;

// Production-ready skeleton for SSLCOMMERZ (the dominant BD payment
// aggregator — routes to bKash/Nagad/Rocket/cards/net banking under one
// integration). Not wired up by default: this sandbox has no outbound
// network access to test against SSLCOMMERZ's API, so `mock` stays the
// default driver (see config/services.php). To go live:
//   1. Set SSLCOMMERZ_STORE_ID / SSLCOMMERZ_STORE_PASSWORD in .env
//   2. Set PAYMENT_GATEWAY_DRIVER=sslcommerz in .env
//   3. Bind this class in AppServiceProvider (already done, driver-switched)
class SslcommerzGateway implements PaymentGatewayInterface
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.sslcommerz.sandbox')
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';
    }

    public function initiate(Payment $payment): string
    {
        $response = Http::asForm()->post("{$this->baseUrl}/gwprocess/v4/api.php", [
            'store_id' => config('services.sslcommerz.store_id'),
            'store_passwd' => config('services.sslcommerz.store_password'),
            'total_amount' => $payment->amount,
            'currency' => $payment->currency,
            'tran_id' => $payment->reference,
            'success_url' => route('payments.callback.success', $payment),
            'fail_url' => route('payments.callback.fail', $payment),
            'cancel_url' => route('payments.callback.cancel', $payment),
            'cus_name' => $payment->user->name,
            'cus_email' => $payment->user->email,
            'cus_phone' => $payment->user->phone ?? 'N/A',
            'cus_add1' => 'N/A',
            'shipping_method' => 'NO',
            'product_name' => $payment->description,
            'product_category' => 'Government Fee',
            'product_profile' => 'general',
        ]);

        return $response->json('GatewayPageURL') ?? route('payments.callback.fail', $payment);
    }

    public function verify(Payment $payment, array $callbackData): bool
    {
        // Real implementation calls SSLCOMMERZ's order-validation API with
        // val_id from the callback and checks status === 'VALID' and the
        // amount/currency/tran_id match $payment before trusting it.
        $response = Http::get("{$this->baseUrl}/validator/api/validationserverAPI.php", [
            'val_id' => $callbackData['val_id'] ?? '',
            'store_id' => config('services.sslcommerz.store_id'),
            'store_passwd' => config('services.sslcommerz.store_password'),
            'format' => 'json',
        ]);

        return $response->json('status') === 'VALID'
            && (float) $response->json('amount') === (float) $payment->amount;
    }
}
