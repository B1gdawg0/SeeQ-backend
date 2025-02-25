<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'password' => static::$password ??= Hash::make('password'),
            'image_url' => $this->faker->optional(0.3)->imageUrl(640, 480, 'business'), // 30% จะเป็น null
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'description' => $this->faker->text(200),
            'is_open' => $this->faker->boolean,
            'latitude' => $this->faker->latitude(13.8200, 13.8500),
            'longitude' => $this->faker->longitude(100.5600, 100.5800),
        ];
    }
}
