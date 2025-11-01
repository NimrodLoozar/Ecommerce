<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TradeIn>
 */
class TradeInFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currentYear = now()->year;
        $year = fake()->numberBetween($currentYear - 15, $currentYear - 1);
        $mileage = fake()->numberBetween(5000, 250000);

        return [
            'user_id' => User::factory(),
            'brand_id' => Brand::factory(),
            'car_model_id' => CarModel::factory(),
            'year' => $year,
            'mileage' => $mileage,
            'condition' => fake()->randomElement(['excellent', 'good', 'fair', 'poor']),
            'exterior_color' => fake()->randomElement(['Black', 'White', 'Silver', 'Gray', 'Blue', 'Red', 'Green']),
            'interior_color' => fake()->randomElement(['Black', 'Beige', 'Gray', 'Brown']),
            'vin_number' => strtoupper(fake()->bothify('??#############')),
            'license_plate' => strtoupper(fake()->bothify('??-###-??')),
            'ownership_status' => fake()->randomElement(['owned', 'financed', 'leased']),
            'accidents' => fake()->boolean(30),
            'service_history' => fake()->randomElement(['full', 'partial', 'none']),
            'description' => fake()->paragraph(3),
            'estimated_value' => fake()->randomFloat(2, 5000, 80000),
            'offer_amount' => null,
            'offer_expires_at' => null,
            'status' => 'pending',
            'reviewed_by' => null,
            'reviewed_at' => null,
            'notes' => null,
        ];
    }

    /**
     * Indicate that the trade-in is under review.
     */
    public function underReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'under_review',
            'reviewed_by' => User::factory()->dealer(),
            'reviewed_at' => now()->subHours(rand(1, 48)),
        ]);
    }

    /**
     * Indicate that an offer has been made.
     */
    public function offerMade(): static
    {
        $estimatedValue = fake()->randomFloat(2, 5000, 80000);
        $offerAmount = $estimatedValue * fake()->randomFloat(2, 0.75, 0.95); // Offer 75-95% of estimated value

        return $this->state(fn (array $attributes) => [
            'status' => 'offer_made',
            'estimated_value' => $estimatedValue,
            'offer_amount' => round($offerAmount, 2),
            'offer_expires_at' => now()->addDays(rand(7, 14)),
            'reviewed_by' => User::factory()->dealer(),
            'reviewed_at' => now()->subHours(rand(1, 24)),
            'notes' => fake()->sentence(10),
        ]);
    }

    /**
     * Indicate that the offer has been accepted.
     */
    public function accepted(): static
    {
        $estimatedValue = fake()->randomFloat(2, 5000, 80000);
        $offerAmount = $estimatedValue * fake()->randomFloat(2, 0.80, 0.95);

        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
            'estimated_value' => $estimatedValue,
            'offer_amount' => round($offerAmount, 2),
            'offer_expires_at' => now()->addDays(rand(7, 14)),
            'reviewed_by' => User::factory()->dealer(),
            'reviewed_at' => now()->subDays(rand(1, 5)),
            'notes' => 'Customer accepted the offer.',
        ]);
    }

    /**
     * Indicate that the offer has been rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'offer_amount' => fake()->randomFloat(2, 5000, 60000),
            'offer_expires_at' => now()->subDays(rand(1, 10)),
            'reviewed_by' => User::factory()->dealer(),
            'reviewed_at' => now()->subDays(rand(1, 10)),
            'notes' => 'Customer rejected the offer.',
        ]);
    }

    /**
     * Indicate that the trade-in has been completed.
     */
    public function completed(): static
    {
        $estimatedValue = fake()->randomFloat(2, 5000, 80000);
        $offerAmount = $estimatedValue * fake()->randomFloat(2, 0.80, 0.95);

        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'estimated_value' => $estimatedValue,
            'offer_amount' => round($offerAmount, 2),
            'offer_expires_at' => now()->addDays(7),
            'reviewed_by' => User::factory()->dealer(),
            'reviewed_at' => now()->subDays(rand(5, 15)),
            'notes' => 'Trade-in successfully completed and applied to order.',
        ]);
    }

    /**
     * Indicate that the trade-in has been cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'reviewed_by' => User::factory()->dealer(),
            'reviewed_at' => now()->subDays(rand(1, 5)),
            'notes' => fake()->randomElement([
                'Customer cancelled the request.',
                'Vehicle does not meet trade-in criteria.',
                'Unable to verify vehicle information.',
            ]),
        ]);
    }

    /**
     * Trade-in with accident history.
     */
    public function withAccidents(): static
    {
        return $this->state(fn (array $attributes) => [
            'accidents' => true,
            'condition' => fake()->randomElement(['fair', 'poor']),
            'estimated_value' => fake()->randomFloat(2, 3000, 25000),
        ]);
    }

    /**
     * Trade-in in excellent condition.
     */
    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'condition' => 'excellent',
            'accidents' => false,
            'service_history' => 'full',
            'estimated_value' => fake()->randomFloat(2, 20000, 80000),
            'mileage' => fake()->numberBetween(5000, 50000),
        ]);
    }
}
