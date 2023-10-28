<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::factory()->create([
            'name' => 'Discover the Stargate<stargate>',
            'description' => 'Go to the Stargate and discover it. <stargate>',
            'assigned_user_id' => User::query()->where(['user_name' => 'daniel'])->firstOrFail()->id,
            'estimated_time' => strtotime('10 day 3 hours', 0),
            'spent_time' => strtotime('1 day 3 hours', 0),
            'completed_at' => null,
        ]);
        Task::factory()->create([
            'name' => 'Go to Abydos',
            'description' => 'Go to Abydos and find the Eye of Ra.',
            'assigned_user_id' => User::query()->where(['user_name' => 'tealc'])->firstOrFail()->id,
            'estimated_time' => strtotime('1 day 3 hours', 0),
            'completed_at' => now(),
            'spent_time' => 0,
        ]);
        Task::factory()->create([
            'name' => 'Go to Chulak',
            'description' => null,
            'estimated_time' => null,
            'spent_time' => 0,
            'completed_at' => null,
        ]);
        Task::factory()->create([
            'name' => 'Go to Chulak again',
            'assigned_user_id' => User::query()->where(['user_name' => 'samantha'])->firstOrFail()->id,
            'estimated_time' => strtotime('1 day 3 hours', 0),
            'description' => null,
            'spent_time' => 0,
            'completed_at' => null,
        ]);
        Task::factory()->create([
            'name' => 'Find Apophis',
            'assigned_user_id' => User::query()->where(['user_name' => 'daniel'])->firstOrFail()->id,
            'spent_time' => strtotime('30 day 1 hours 13 minutes 3 seconds', 0),
            'description' => null,
            'completed_at' => null,
        ]);

        Task::factory()->count(10)->create();
    }
}
