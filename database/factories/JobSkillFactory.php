<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobSkillFactory extends Factory
{
    protected $model = JobSkill::class;

    public function definition(): array
    {
        return [
            'job_id'     => Job::factory(),
            'skill_name' => $this->faker->word(),
            'level'      => $this->faker->randomElement(['required', 'nice_to_have']),
            'matched'    => $this->faker->boolean(),
        ];
    }
}
