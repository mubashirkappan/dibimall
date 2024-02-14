<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class userTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::factory()
            ->create([
                'name' => 'Admin',
                'email' => 'Admin@dibimall.com',
                'password' => "asdasdasd",
            ]);
    }
}
