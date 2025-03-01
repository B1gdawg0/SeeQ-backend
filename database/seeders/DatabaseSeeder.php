<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Queue;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(10)->create();
         Queue::factory(5)->create();
         Item::factory(50)->create();


//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
        $this->call([
            UserSeeder::class,
            ShopSeeder::class,
        ]);
    }
}
