<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Events\DonationReceived;
use App\Listeners\LogFinancialTransaction;
use App\Listeners\SendCertificateNotification;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the payment contract to the Stripe implementation.
        // Swap this single line to change gateways across the entire app.
        $this->app->bind(PaymentGatewayInterface::class, StripePaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(DonationReceived::class, SendCertificateNotification::class);
        Event::listen(DonationReceived::class, LogFinancialTransaction::class);
    }
}
