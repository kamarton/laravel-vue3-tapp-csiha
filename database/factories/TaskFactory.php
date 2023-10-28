<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{

    public function definition(): array
    {
        $array = [
            'name' => fake()->unique()->sentence(3),
            'description' => fake()->paragraph(mt_rand(1, 5)),
        ];
        if (mt_rand(0, 1) === 1) {
            $array['assigned_user_id'] = User::query()->inRandomOrder()->firstOrFail()->id;
        }
        if (mt_rand(0, 1) === 1) {
            $array['estimated_time'] = fake()->numberBetween(
                strtotime('1 minute', 0),
                strtotime('1 month', 0)
            );
        }
        if (mt_rand(0, 1) === 1) {
            $array['spent_time'] = fake()->numberBetween(
                strtotime('1 minute', 0),
                strtotime('1 month', 0)
            );
        }
        if (mt_rand(0, 1) === 1) {
            $array['completed_at'] = fake()->dateTimeBetween('-1 years');
        }
        return $array;
    }
}
