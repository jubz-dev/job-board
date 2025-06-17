<?php

namespace Database\Factories;

use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<JobPost>
 */
class JobPostFactory extends Factory
{
    protected $model = JobPost::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'email' => $this->faker->safeEmail,
            'status' => JobPost::STATUS_PENDING,
            'source' => JobPost::SOURCE_INTERNAL,
            'source_url' => null,
        ];
    }
}
