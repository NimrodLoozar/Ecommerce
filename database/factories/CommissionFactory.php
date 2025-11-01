<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\DealerProfile;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commission>
 */
class CommissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $saleAmount = fake()->randomFloat(2, 10000, 150000);
        $commissionRate = fake()->randomFloat(2, 3.00, 15.00);
        $commissionAmount = round($saleAmount * ($commissionRate / 100), 2);

        return [
            'dealer_id' => DealerProfile::factory()->approved(),
            'order_id' => Order::factory(),
            'car_id' => Car::factory(),
            'sale_amount' => $saleAmount,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'status' => 'pending',
            'paid_at' => null,
            'payment_method' => null,
            'payment_reference' => null,
        ];
    }

    /**
     * Indicate that the commission is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the commission is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now()->subDays(rand(1, 30)),
            'payment_method' => fake()->randomElement(['bank_transfer', 'paypal', 'stripe', 'direct_deposit']),
            'payment_reference' => strtoupper(fake()->bothify('PAY-####-????-####')),
        ]);
    }

    /**
     * Create a commission with a specific amount.
     */
    public function withAmount(float $saleAmount, float $commissionRate): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_amount' => $saleAmount,
            'commission_rate' => $commissionRate,
            'commission_amount' => round($saleAmount * ($commissionRate / 100), 2),
        ]);
    }

    /**
     * Create a high-value commission (luxury car).
     */
    public function luxury(): static
    {
        $saleAmount = fake()->randomFloat(2, 80000, 250000);
        $commissionRate = fake()->randomFloat(2, 5.00, 12.00);

        return $this->state(fn (array $attributes) => [
            'sale_amount' => $saleAmount,
            'commission_rate' => $commissionRate,
            'commission_amount' => round($saleAmount * ($commissionRate / 100), 2),
        ]);
    }
}
