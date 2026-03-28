<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'title'              => $this->faker->jobTitle(),
            'company'            => $this->faker->company(),
            'source_name'        => $this->faker->randomElement(['LinkedIn', 'Gupy', 'Indeed', null]),
            'source_url'         => $this->faker->optional()->url(),
            'apply_url'          => $this->faker->optional()->url(),
            'description'        => $this->faker->optional()->paragraph(),
            'salary_expectation' => $this->faker->optional()->randomFloat(2, 3000, 30000),
            'status'             => $this->faker->randomElement([
                'identified', 'applied', 'recruiter_interview', 'technical_interview',
                'technical_test', 'offer', 'hired', 'rejected', 'ghosted',
            ]),
            'notes'       => $this->faker->optional()->sentence(),
            'applied_at'  => $this->faker->optional()->date(),
        ];
    }

    public function identified(): static
    {
        return $this->state(['status' => 'identified', 'applied_at' => null]);
    }

    public function applied(): static
    {
        return $this->state(['status' => 'applied', 'applied_at' => now()->toDateString()]);
    }

    public function hired(): static
    {
        return $this->state(['status' => 'hired', 'applied_at' => now()->subDays(30)->toDateString()]);
    }
}
