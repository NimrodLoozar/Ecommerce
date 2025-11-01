<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaseAgreement>
 */
class LeaseAgreementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $car = Car::factory()->create();
        $order = Order::factory()->lease()->create();
        $leaseDuration = fake()->randomElement([24, 36, 48, 60]); // months
        $monthlyPayment = $car->lease_price_monthly ?? ($car->price * 0.015);
        $downPayment = $car->price * fake()->randomFloat(2, 0.1, 0.3); // 10-30% down
        $startDate = fake()->dateTimeBetween('now', '+30 days');
        $endDate = (clone $startDate)->modify("+{$leaseDuration} months");

        return [
            'order_id' => $order->id,
            'car_id' => $car->id,
            'user_id' => $order->user_id,
            'lease_duration' => $leaseDuration,
            'monthly_payment' => $monthlyPayment,
            'down_payment' => $downPayment,
            'annual_mileage_limit' => fake()->randomElement([10000, 15000, 20000, 25000]),
            'excess_mileage_charge' => fake()->randomFloat(2, 0.10, 0.25), // per km
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(['pending', 'active', 'completed']),
            'contract_file' => fake()->boolean(50) ? 'contracts/' . fake()->uuid() . '.pdf' : null,
        ];
    }

    /**
     * Indicate that the lease is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'start_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'contract_file' => 'contracts/' . fake()->uuid() . '.pdf',
        ]);
    }

    /**
     * Indicate that the lease is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'start_date' => fake()->dateTimeBetween('now', '+30 days'),
        ]);
    }

    /**
     * Indicate that the lease is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'start_date' => fake()->dateTimeBetween('-3 years', '-1 year'),
            'end_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }
}
