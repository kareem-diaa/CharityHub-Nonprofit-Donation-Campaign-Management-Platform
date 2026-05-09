<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripePaymentService implements PaymentGatewayInterface
{
    public function __construct()
    {
        // Reads from config/services.php → never from env() directly
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe Checkout Session for one-time or recurring donations.
     *
     * Expected $params keys (see PaymentGatewayInterface for full spec):
     *   amount, currency, product_name, mode, success_url, cancel_url
     *   Optional: customer_email, interval (default 'month' for subscriptions)
     */
    public function createCheckoutSession(array $params): string
    {
        $mode     = $params['mode'];          // 'payment' | 'subscription'
        $interval = $params['interval'] ?? 'month';

        // Build price_data — recurring requires the nested 'recurring' key
        $priceData = [
            'currency'     => $params['currency'],
            'product_data' => [
                'name' => $params['product_name'],
            ],
            'unit_amount'  => $params['amount'], // already in cents
        ];

        if ($mode === 'subscription') {
            $priceData['recurring'] = ['interval' => $interval];
        }

        $sessionParams = [
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price_data' => $priceData,
                'quantity'   => 1,
            ]],
            'mode'        => $mode,
            'success_url' => $params['success_url'],
            'cancel_url'  => $params['cancel_url'],
        ];

        // Pre-fill email when available (optional)
        if (!empty($params['customer_email'])) {
            $sessionParams['customer_email'] = $params['customer_email'];
        }

        $session = Session::create($sessionParams);

        return $session->url;
    }

    /**
     * Retrieve a Stripe Checkout Session by its ID.
     * Returns the raw Stripe Session object — it exposes payment_status,
     * payment_intent, subscription, and customer_email natively.
     */
    public function retrieveSession(string $sessionId): object
    {
        return Session::retrieve($sessionId);
    }
}
