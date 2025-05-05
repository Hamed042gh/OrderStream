<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' =>  \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['pending', 'paid', 'canceled']), // مقدار به‌صورت رشته
            'total_price'=> $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
