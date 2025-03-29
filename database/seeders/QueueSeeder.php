<?php

namespace Database\Seeders;

use App\Models\Queue;
use App\Models\Shop;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Queue::factory(Shop::all()->count())->create();

        $queue = Queue::create([
            'name'=> 'โต๊ะทั่วไป',
            'description' => 'โต๊ะ 4 คน',
            'is_available' => true,
            'tag' => 'A',
            'shop_id' => Shop::where('name', 'Starbucks')->first()->id,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $name = 'user' . $i + 1;
            $user = User::where('name', $name)->first();

            if ($user) {
                DB::table('users_queues')->insert([
                    'user_id' => $user->id,
                    'queue_id' => $queue->id,
                    'queue_number' => 'A' . $queue->queue_counter,
                    'status' => 'waiting',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $queue->queue_counter++;
                $queue->save();
            }
        }
    }
}
