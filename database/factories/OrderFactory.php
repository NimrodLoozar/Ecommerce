<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
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
        $subtotal = fake()->numberBetween(15000, 80000);
        $tax = $subtotal * 0.21; // 21% VAT
        $deliveryFee = fake()->randomElement([0, 500, 1000]);
        $total = $subtotal + $tax + $deliveryFee;

        $user = User::factory()->create();
        $billingAddress = Address::factory()->billing()->create(['user_id' => $user->id]);
        $shippingAddress = Address::factory()->shipping()->create(['user_id' => $user->id]);

        return [
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(fake()->unique()->bothify('????-####')),
            'purchase_type' => fake()->randomElement(['purchase', 'lease']),
            'status' => fake()->randomElement(['pending', 'confirmed', 'processing', 'completed']),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'currency' => 'EUR',
            'payment_method' => fake()->randomElement(['credit_card', 'bank_transfer', 'financing']),
            'payment_status' => fake()->randomElement(['pending', 'paid']),
            'billing_address_id' => $billingAddress->id,
            'shipping_address_id' => $shippingAddress->id,
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
            'admin_notes' => fake()->boolean(20) ? fake()->sentence() : null,
            'completed_at' => null,
            'cancelled_at' => null,
        ];
    }

    /**
     * Indicate that the order is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'payment_status' => 'paid',
            'completed_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the order is for a lease.
     */
    public function lease(): static
    {
        return $this->state(fn (array $attributes) => [
            'purchase_type' => 'lease',
        ]);
    }
}
