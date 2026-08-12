<?php

namespace App\Providers;

use App\Services\Payment\MockGateway;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\SslcommerzGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, function () {
            return match (config('services.payment.driver', 'mock')) {
                'sslcommerz' => new SslcommerzGateway,
                default => new MockGateway,
            };
        });
    }
}
