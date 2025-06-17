<?php

namespace Tests\Feature;

use App\Events\JobStatusUpdated;
use App\Models\JobPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ModeratorControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_403_for_invalid_signature()
    {
        $jobPost = JobPost::factory()->create();

        $response = $this->get(
            route('moderate', ['jobPost' => $jobPost->id, 'action' => 'approve'])
        );

        $response->assertStatus(403);
    }

    /** @test */
    public function it_returns_400_for_invalid_action()
    {
        $jobPost = JobPost::factory()->create();

        $signedUrl = URL::signedRoute('moderate', [
            'jobPost' => $jobPost->id,
            'action' => 'invalid_action'
        ]);

        $response = $this->get($signedUrl);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_updates_status_and_dispatches_event()
    {
        Event::fake();

        $jobPost = JobPost::factory()->create();

        $signedUrl = URL::signedRoute('moderate', [
            'jobPost' => $jobPost->id,
            'action' => 'approve'
        ]);

        $response = $this->get($signedUrl);

        $response->assertRedirectContains('/moderate-result?action=approve');

        $this->assertDatabaseHas('job_posts', [
            'id' => $jobPost->id,
            'status' => 'approve'
        ]);

        Event::assertDispatched(JobStatusUpdated::class);
    }
}
