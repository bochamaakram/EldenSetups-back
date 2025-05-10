<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Check if users table is empty
        if (User::count() === 0) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@elden.com',
                'password' => Hash::make('password'), // change this!
                'role' => 'admin',
            ]);
            
            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}