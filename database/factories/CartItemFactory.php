<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $car = Car::factory()->create();
        $purchaseType = fake()->randomElement(['purchase', 'lease']);

        return [
            'cart_id' => Cart::factory(),
            'car_id' => $car->id,
            'quantity' => 1, // Typically 1 for cars
            'purchase_type' => $purchaseType,
            'lease_duration' => $purchaseType === 'lease' ? fake()->randomElement([24, 36, 48, 60]) : null,
            'price_snapshot' => $purchaseType === 'lease' ? $car->lease_price_monthly : $car->price,
        ];
    }

    /**
     * Indicate that this is a purchase item.
     */
    public function purchase(): static
    {
        return $this->state(function (array $attributes) {
            $car = Car::find($attributes['car_id']) ?? Car::factory()->create();
            return [
                'purchase_type' => 'purchase',
                'lease_duration' => null,
                'price_snapshot' => $car->price,
            ];
        });
    }

    /**
     * Indicate that this is a lease item.
     */
    public function lease(): static
    {
        return $this->state(function (array $attributes) {
            $car = Car::find($attributes['car_id']) ?? Car::factory()->create();
            return [
                'purchase_type' => 'lease',
                'lease_duration' => fake()->randomElement([24, 36, 48, 60]),
                'price_snapshot' => $car->lease_price_monthly ?? $car->price * 0.015,
            ];
        });
    }
}
