<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reminder>
 */
class ReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'completed']);

        if ($status === 'completed') {
            $dueDate = fake()->dateTimeBetween('-2 days', '-1 day');
        } else {
            $dueDate = fake()->dateTimeBetween('+1 day', '+2 days');
        }

        return [
            'shop_id' => 1,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'due_date' => $dueDate,
            'status' => $status,
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'updated_at' => function (array $attributes) {
                return fake()->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}
