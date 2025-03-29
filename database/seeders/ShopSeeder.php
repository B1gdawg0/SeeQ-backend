<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shop::factory()->count(User::where('role', 'SHOP')->get()->count())->create();

        $user1 = User::create([
            "name" => "Starbucks",
            "email" => "starbuck@gmail.com",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "02-613-1234",
        ]);

        Shop::create([
            "user_id" => $user1->id,
            "name" => "Starbucks",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "086-613-1234",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "Starbucks Coffee Company is the leading retailer, roaster and brand of specialty coffee in the world.",
            "is_open" => true,
            "latitude" => 13.8475,
            "longitude" => 100.5710
        ]);

        $user2 = User::create([
            "name" => "McDonald's",
            "email" => "mcDonald@gmail.com",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "080-658-0200",
        ]);

        Shop::create([
            "user_id" => $user2->id,
            "name" => "McDonald's",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "02-613-1234",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "McDonald's Corporation is an American fast food company, founded in 1940.",
            "is_open" => true,
            "latitude" => 13.8492,
            "longitude" => 100.5735
        ]);

        $user3 = User::create([
            "name" => "KFC",
            "email" => "kfc@kfc.co.th",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-611-7555",
        ]);

        Shop::create([
            "user_id" => $user3->id,
            "name" => "KFC",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-611-7555",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "KFC specializes in fried chicken.",
            "is_open" => true,
            "latitude" => 13.8450,
            "longitude" => 100.5687
        ]);

        $user4 = User::create([
            "name" => "Burger King",
            "email" => "burgerking@burgerking.co.th",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-108-0880",
        ]);

        Shop::create([
            "user_id" => $user4->id,
            "name" => "Burger King",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-108-0880",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "Burger King is a global fast food chain.",
            "is_open" => true,
            "latitude" => 13.8468,
            "longitude" => 100.5665
        ]);

        $user5 = User::create([
            "name" => "After You",
            "email" => "afteryou@afteryou.co.th",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-658-1836",
        ]);

        Shop::create([
            "user_id" => $user5->id,
            "name" => "After You",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-658-1836",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "After You Dessert Cafe is a popular dessert cafe originated from Thailand.",
            "is_open" => true,
            "latitude" => 13.8503,
            "longitude" => 100.5702
        ]);

        $user6 = User::create([
            "name" => "Bonchon Chicken",
            "email" => "bonchon@bonchon.co.th",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-003-6202",
        ]);

        Shop::create([
            "user_id" => $user6->id,
            "name" => "Bonchon Chicken",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-003-6202",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "Bonchon Chicken is a South Korean-based fried chicken franchise.",
            "is_open" => true,
            "latitude" => 13.8481,
            "longitude" => 100.5725
        ]);

        $user7 = User::create([
            "name" => "Swensen’s",
            "email" => "swensen@Swensen.co.th",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-363-3150",
        ]);

        Shop::create([
            "user_id" => $user7->id,
            "name" => "Swensen’s",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-363-3150",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "Swensen's is a global chain of ice cream restaurants.",
            "is_open" => true,
            "latitude" => 13.8479,
            "longitude" => 100.5678
        ]);

        $user8 = User::create([
            "name" => "MK",
            "email" => "mk@mk.co.th",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-958-0880",
        ]);

        Shop::create([
            "user_id" => $user8->id,
            "name" => "MK",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-958-0880",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "MK Restaurant is a popular Thai sukiyaki chain.",
            "is_open" => true,
            "latitude" => 13.8495,
            "longitude" => 100.5697
        ]);

        // User 9
        $user9 = User::create([
            "name" => "Sizzler",
            "email" => "sizzler@sizzler.co.th",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-105-1555",
        ]);

        Shop::create([
            "user_id" => $user9->id,
            "name" => "Sizzler",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-105-1555",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "Sizzler is a United States-based restaurant chain specializing in steaks and salads.",
            "is_open" => true,
            "latitude" => 13.8500,
            "longitude" => 100.5682
        ]);

        // User 10
        $user10 = User::create([
            "name" => "Shabu Shi",
            "email" => "shabushi@shabushi.co.th",
            "password" => Hash::make("password"),
            "role" => "SHOP",
            "email_verified_at" => now(),
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-721-8888",
        ]);

        Shop::create([
            "user_id" => $user10->id,
            "name" => "Shabu Shi",
            "image_url" => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            "phone" => "062-721-8888",
            "address" => "Kasetsart University, Bangkok 10900",
            "description" => "Shabushi is a popular Japanese shabu-shabu and sushi buffet restaurant chain in Thailand.",
            "is_open" => true,
            "latitude" => 13.8463,
            "longitude" => 100.5708
        ]);
    }
}
