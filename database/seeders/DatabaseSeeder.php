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
        $this->call([
            UserSeeder::class,
            ShopSeeder::class,
            ReminderSeeder::class,
            QueueSeeder::class,
        ]);
    }
}
