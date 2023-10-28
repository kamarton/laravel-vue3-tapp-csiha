<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'user_name' => 'daniel',
            'email' => 'daniel.jackson@sg1.stargate'
        ]);
        User::factory()->create([
            'user_name' => 'samantha',
            'email' => 'samantha.carter@sg1.stargate',
        ]);
        User::factory()->create([
            'user_name' => 'jack',
            'email' => 'jack.o.neill@sg1.stargate',
        ]);
        User::factory()->create([
            'user_name' => 'tealc',
            'email' => 'teal.c@sg1.stargate',
        ]);

        User::factory()->count(5)->create();
    }
}
