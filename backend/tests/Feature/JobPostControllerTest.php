<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\JobPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\FirstJobSubmissionMail;

class JobPostControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_public_job_posts()
    {
        JobPost::factory()->create([
            'status' => JobPost::STATUS_APPROVED,
            'source' => JobPost::SOURCE_INTERNAL,
        ]);

        JobPost::factory()->create([
            'status' => JobPost::STATUS_PENDING,
            'source' => JobPost::SOURCE_INTERNAL,
        ]);

        JobPost::factory()->create([
            'source' => JobPost::SOURCE_EXTERNAL,
        ]);

        $response = $this->getJson('/api/jobPosts');

        $response->assertStatus(200);
        $response->assertJsonCount(2); // approved internal + external
    }

    /** @test */
    public function it_validates_required_fields_on_submission()
    {
        $response = $this->postJson('/api/jobPosts', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description', 'email']);
    }

    /** @test */
    public function it_creates_job_post_and_sends_email_if_first_submission()
    {
        Mail::fake();

        $payload = [
            'title' => 'Junior Developer',
            'description' => 'Great opportunity.',
            'email' => 'testuser@example.com',
        ];

        $response = $this->postJson('/api/jobPosts', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('job_posts', [
            'email' => $payload['email'],
            'status' => JobPost::STATUS_PENDING,
        ]);

        Mail::assertSent(FirstJobSubmissionMail::class, 1);
    }

    /** @test */
    public function it_does_not_send_email_if_not_first_job_by_email()
    {
        Mail::fake();

        JobPost::factory()->create([
            'email' => 'testuser@example.com',
        ]);

        $response = $this->postJson('/api/jobPosts', [
            'title' => 'Another Job',
            'description' => 'Cool role.',
            'email' => 'testuser@example.com',
        ]);

        $response->assertCreated();

        Mail::assertNothingSent();
    }
}
