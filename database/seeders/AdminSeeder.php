<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin123',
            'password' => Hash::make('admin123'), // Change 'password' to your preferred admin password
            'role' => 'Admin',
            // Add any other required fields here, like 'email' if needed
        ]);
    }
}
