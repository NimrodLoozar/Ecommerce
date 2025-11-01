<?php

namespace Database\Factories;

use App\Models\TradeIn;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TradeInImage>
 */
class TradeInImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['exterior', 'interior', 'damage', 'documents']);

        return [
            'trade_in_id' => TradeIn::factory(),
            'image_path' => 'trade-ins/' . fake()->uuid() . '.jpg',
            'type' => $type,
            'display_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Exterior image.
     */
    public function exterior(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'exterior',
            'display_order' => fake()->numberBetween(1, 6),
        ]);
    }

    /**
     * Interior image.
     */
    public function interior(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'interior',
            'display_order' => fake()->numberBetween(1, 4),
        ]);
    }

    /**
     * Damage image.
     */
    public function damage(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'damage',
            'display_order' => fake()->numberBetween(1, 3),
        ]);
    }

    /**
     * Document image (registration, service records, etc.).
     */
    public function documents(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'documents',
            'image_path' => 'trade-ins/documents/' . fake()->uuid() . '.pdf',
            'display_order' => fake()->numberBetween(1, 5),
        ]);
    }
}
