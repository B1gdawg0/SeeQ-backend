<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(100)->create();
        User::create([
            "name" => "Admin",
            "email" => "admin@admin.com",
            "password" => Hash::make("password"),
            "role" => "ADMIN",
            "email_verified_at" => now(),
        ]);
    }
}
