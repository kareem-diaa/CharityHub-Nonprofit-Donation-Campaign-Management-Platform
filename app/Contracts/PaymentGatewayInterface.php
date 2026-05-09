<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Create a checkout session on the payment gateway.
     *
     * Accepts a normalised parameter array so the caller never knows
     * which gateway is underneath. Returns the redirect URL the user
     * should be sent to in order to complete payment.
     *
     * Required keys:
     *   - amount        (int)    Amount in cents (e.g. 5000 = $50.00)
     *   - currency      (string) ISO 4217 lowercase (e.g. 'usd')
     *   - product_name  (string) Human-readable label shown on the checkout page
     *   - mode          (string) 'payment' for one-time | 'subscription' for recurring
     *   - success_url   (string) Full URL Stripe redirects to on success
     *   - cancel_url    (string) Full URL Stripe redirects to on cancel
     *
     * Optional keys:
     *   - customer_email (string|null) Pre-fills the email field on checkout
     *   - interval       (string)      Billing interval for subscriptions ('month'|'year')
     *
     * @param  array<string, mixed> $params
     * @return string  The gateway-hosted checkout URL
     */
    public function createCheckoutSession(array $params): string;

    /**
     * Retrieve a previously created checkout session by its gateway ID.
     *
     * Returns a plain object / DTO that exposes at minimum:
     *   - payment_status   (string)       'paid' | 'unpaid' | 'no_payment_required'
     *   - payment_intent   (string|null)  Transaction reference for one-time payments
     *   - subscription     (string|null)  Subscription ID for recurring payments
     *   - customer_email   (string|null)
     *
     * @param  string $sessionId  The gateway session identifier
     * @return object
     */
    public function retrieveSession(string $sessionId): object;
}
