<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobTimeline;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobTimelineFactory extends Factory
{
    protected $model = JobTimeline::class;

    public function definition(): array
    {
        return [
            'job_id'      => Job::factory(),
            'stage'       => $this->faker->randomElement([
                'identified', 'applied', 'recruiter_interview', 'technical_interview',
                'technical_test', 'offer', 'hired',
            ]),
            'happened_at' => $this->faker->date(),
            'notes'       => $this->faker->optional()->sentence(),
        ];
    }
}
