<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reminder;
use App\Models\Shop;

class ReminderSeeder extends Seeder
{
    public function run(): void
    {
            Reminder::factory(20)->create();
    }
}
