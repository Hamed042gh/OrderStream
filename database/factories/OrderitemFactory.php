<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orderitem>
 */
class OrderitemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' =>  \App\Models\Order::factory(),
            'product_id' =>  \App\Models\Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit_price' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
