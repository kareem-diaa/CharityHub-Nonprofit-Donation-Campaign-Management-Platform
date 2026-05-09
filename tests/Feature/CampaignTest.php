<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_upload_stores_file_correctly()
    {
        Storage::fake('public');

        // Setup permission for the user
        Permission::firstOrCreate(['name' => 'manage_campaigns']);
        
        $admin = User::factory()->create();
        $admin->givePermissionTo('manage_campaigns');

        $file = UploadedFile::fake()->image('campaign.jpg');

        $response = $this->actingAs($admin)->post(route('campaigns_store'), [
            'title' => 'Test Campaign',
            'slug' => 'test-campaign',
            'description' => 'Test description',
            'goal_amount' => 5000,
            'deadline' => now()->addDays(30)->format('Y-m-d'),
            'image' => $file,
        ]);

        $response->assertRedirect(route('campaigns_list'));
        $response->assertSessionHas('success');

        $campaign = Campaign::where('slug', 'test-campaign')->first();
        $this->assertNotNull($campaign);
        $this->assertNotNull($campaign->image);

        // Assert the file was stored on the fake disk
        Storage::disk('public')->assertExists($campaign->image);
    }

    public function test_slug_routing_resolves_correct_campaign()
    {
        $campaign = Campaign::forceCreate([
            'title' => 'Save the Oceans',
            'slug' => 'save-the-oceans',
            'description' => 'Help us save the oceans.',
            'goal_amount' => 1000,
            'raised_amount' => 0,
            'deadline' => now()->addDays(10),
            'status' => 'active'
        ]);

        $response = $this->get(route('campaigns_show', $campaign->slug));

        $response->assertStatus(200);
        $response->assertSee('Save the Oceans');
    }
}
