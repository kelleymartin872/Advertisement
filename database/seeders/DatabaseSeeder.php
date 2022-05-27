<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory()->create([
             'email' => 'user_1@example.com',
             'password' => bcrypt('password_1')
         ]);

        \App\Models\User::factory()->create([
            'email' => 'user_2@example.com',
            'password' => bcrypt('password_2')
        ]);
    }
}
