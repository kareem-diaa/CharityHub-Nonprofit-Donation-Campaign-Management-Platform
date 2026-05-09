<?php

namespace Tests\Feature;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_one_time_donation_creates_stripe_checkout_session()
    {
        $user = User::factory()->create();

        $campaign = Campaign::forceCreate([
            'title' => 'Save the Whales',
            'slug' => 'save-the-whales',
            'description' => 'Help us save whales.',
            'goal_amount' => 1000,
            'raised_amount' => 0,
            'deadline' => now()->addDays(10),
            'status' => 'active'
        ]);

        $mock = Mockery::mock(PaymentGatewayInterface::class);
        $mock->shouldReceive('createCheckoutSession')
             ->once()
             ->withArgs(function ($params) {
                 return $params['mode'] === 'payment' && $params['amount'] === 5000;
             })
             ->andReturn('https://checkout.stripe.com/test_url');

        $this->app->instance(PaymentGatewayInterface::class, $mock);

        $response = $this->actingAs($user)->post(route('donations_process', $campaign), [
            'amount' => 50,
            'type' => 'one-time'
        ]);

        $response->assertRedirect('https://checkout.stripe.com/test_url');
    }

    public function test_duplicate_idempotency_key_is_rejected()
    {
        $user = User::factory()->create();
        
        $campaign = Campaign::forceCreate([
            'title' => 'Save the Whales',
            'slug' => 'save-the-whales',
            'description' => 'Help us save whales.',
            'goal_amount' => 1000,
            'raised_amount' => 0,
            'deadline' => now()->addDays(10),
            'status' => 'active'
        ]);

        $idempotencyKey = hash('sha256', implode('|', [
            $user->id,
            $campaign->id,
            50,
            'one-time',
            now()->format('Y-m-d'),
        ]));

        Donation::forceCreate([
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'amount' => 50,
            'type' => 'one-time',
            'idempotency_key' => $idempotencyKey,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($user)->post(route('donations_process', $campaign), [
            'amount' => 50,
            'type' => 'one-time'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'It looks like you have already made this donation today. Thank you!');
    }

    public function test_recurring_donation_sends_subscription_mode_to_stripe()
    {
        $user = User::factory()->create();

        $campaign = Campaign::forceCreate([
            'title' => 'Save the Whales',
            'slug' => 'save-the-whales',
            'description' => 'Help us save whales.',
            'goal_amount' => 1000,
            'raised_amount' => 0,
            'deadline' => now()->addDays(10),
            'status' => 'active'
        ]);

        $mock = Mockery::mock(PaymentGatewayInterface::class);
        $mock->shouldReceive('createCheckoutSession')
             ->once()
             ->withArgs(function ($params) {
                 return $params['mode'] === 'subscription' && $params['amount'] === 5000;
             })
             ->andReturn('https://checkout.stripe.com/test_url_recurring');

        $this->app->instance(PaymentGatewayInterface::class, $mock);

        $response = $this->actingAs($user)->post(route('donations_process', $campaign), [
            'amount' => 50,
            'type' => 'recurring'
        ]);

        $response->assertRedirect('https://checkout.stripe.com/test_url_recurring');
    }
}
