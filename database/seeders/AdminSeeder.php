<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      // Create an admin user
      User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'role' => 'admin', // Assign role as 'admin'
        'password' => Hash::make('admin123'), // Hash the password
    ]);
    }
}
