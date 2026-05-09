<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VolunteerRegistration;
use App\Models\VolunteerTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VolunteerTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_hours_rejects_hours_exceeding_required()
    {
        $user = User::factory()->create();

        $task = VolunteerTask::forceCreate([
            'title' => 'Beach Cleanup',
            'description' => 'Help us clean the beach.',
            'task_date' => now()->addDays(5)->format('Y-m-d'),
            'hours_required' => 5,
            'capacity' => 10,
        ]);

        $registration = VolunteerRegistration::forceCreate([
            'user_id' => $user->id,
            'volunteer_task_id' => $task->id,
            'status' => 'registered',
            'hours_logged' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('volunteers_log_hours', $registration), [
            'hours_logged' => 6 // 6 is greater than hours_required (5)
        ]);

        $response->assertSessionHasErrors('hours_logged');
        
        $this->assertEquals(0, $registration->fresh()->hours_logged);
    }

    public function test_only_registered_volunteers_can_log_hours()
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();

        $task = VolunteerTask::forceCreate([
            'title' => 'Beach Cleanup',
            'description' => 'Help us clean the beach.',
            'task_date' => now()->addDays(5)->format('Y-m-d'),
            'hours_required' => 5,
            'capacity' => 10,
        ]);

        $registration = VolunteerRegistration::forceCreate([
            'user_id' => $owner->id,
            'volunteer_task_id' => $task->id,
            'status' => 'registered',
            'hours_logged' => 0,
        ]);

        $response = $this->actingAs($attacker)->post(route('volunteers_log_hours', $registration), [
            'hours_logged' => 3
        ]);

        $response->assertStatus(403);
        
        $this->assertEquals(0, $registration->fresh()->hours_logged);
    }
}
