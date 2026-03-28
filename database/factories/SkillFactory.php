<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->unique()->word(),
            'category'    => $this->faker->randomElement(['Backend', 'Frontend', 'DevOps', 'Database']),
            'proficiency' => $this->faker->randomElement(['beginner', 'intermediate', 'expert']),
        ];
    }
}
