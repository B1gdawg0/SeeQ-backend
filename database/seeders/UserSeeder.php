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

        for ($i = 0; $i < 5; $i++) {
            User::create([
                "name" => "user" . $i + 1,
                "email" => "user" . $i + 1 . "@gmail.com",
                "password" => Hash::make("password"),
                "role" => "CUSTOMER",
                "email_verified_at" => now(),
                "image_url" => env('APP_URL') . '/api/images/shops/defaults/avatar.png',
                "phone" => fake()->phoneNumber(),
            ]);
        }

    }
}
