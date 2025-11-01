<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::factory()->create();
        $status = fake()->randomElement(['pending', 'completed', 'failed']);

        return [
            'order_id' => $order->id,
            'payment_method' => fake()->randomElement(['credit_card', 'bank_transfer', 'financing', 'paypal']),
            'transaction_id' => fake()->boolean(80) ? 'TXN-' . strtoupper(fake()->bothify('??########')) : null,
            'amount' => $order->total,
            'currency' => 'EUR',
            'status' => $status,
            'payment_gateway' => fake()->randomElement(['stripe', 'paypal', 'mollie', null]),
            'gateway_response' => fake()->boolean(60) ? [
                'status' => $status,
                'message' => fake()->sentence(),
                'reference' => fake()->uuid(),
            ] : null,
            'paid_at' => $status === 'completed' ? fake()->dateTimeBetween('-30 days', 'now') : null,
        ];
    }

    /**
     * Indicate that the payment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'transaction_id' => 'TXN-' . strtoupper(fake()->bothify('??########')),
            'paid_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the payment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }

    /**
     * Indicate that the payment failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'paid_at' => null,
            'gateway_response' => [
                'status' => 'failed',
                'error' => fake()->sentence(),
            ],
        ]);
    }
}
