<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventStore>
 */
class EventStoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'event_type' => $this->faker->word(),
            'event_data' => json_encode([
                'key1' => $this->faker->word,
                'key2' => $this->faker->randomNumber(),
            ]), // مقداردهی JSON به صورت صحیح
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
