<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobContactFactory extends Factory
{
    protected $model = JobContact::class;

    public function definition(): array
    {
        return [
            'job_id'       => Job::factory(),
            'name'         => $this->faker->name(),
            'role'         => $this->faker->optional()->jobTitle(),
            'email'        => $this->faker->optional()->safeEmail(),
            'phone'        => $this->faker->optional()->phoneNumber(),
            'linkedin_url' => $this->faker->optional()->url(),
            'notes'        => $this->faker->optional()->sentence(),
        ];
    }
}
