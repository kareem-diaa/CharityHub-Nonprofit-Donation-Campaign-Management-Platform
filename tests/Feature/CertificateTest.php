<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_donor_can_download_their_own_certificate()
    {
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'User']);

        $user = User::factory()->create();
        $user->assignRole('User');

        $campaign = Campaign::forceCreate([
            'title' => 'Save the Whales',
            'slug' => 'save-the-whales',
            'description' => 'Help us save whales.',
            'goal_amount' => 1000,
            'raised_amount' => 0,
            'deadline' => now()->addDays(10),
            'status' => 'active'
        ]);

        $donation = Donation::forceCreate([
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'amount' => 50,
            'type' => 'one-time',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($user)->get(route('donations_certificate', $donation));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_user_cannot_download_someone_elses_certificate()
    {
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'User']);

        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $attacker->assignRole('User');

        $campaign = Campaign::forceCreate([
            'title' => 'Save the Whales',
            'slug' => 'save-the-whales',
            'description' => 'Help us save whales.',
            'goal_amount' => 1000,
            'raised_amount' => 0,
            'deadline' => now()->addDays(10),
            'status' => 'active'
        ]);

        $donation = Donation::forceCreate([
            'campaign_id' => $campaign->id,
            'user_id' => $owner->id,
            'amount' => 50,
            'type' => 'one-time',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($attacker)->get(route('donations_certificate', $donation));

        $response->assertStatus(403);
    }
}
