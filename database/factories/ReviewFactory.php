<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = fake()->numberBetween(1, 5);

        // Generate review titles based on rating
        $titles = [
            5 => ['Exceptional car!', 'Perfect in every way', 'Absolutely love it!', 'Best purchase ever', 'Highly recommend'],
            4 => ['Very good car', 'Great experience', 'Really satisfied', 'Worth the money', 'Good choice'],
            3 => ['Decent car', 'Meets expectations', 'Average experience', 'It\'s okay', 'Fair for the price'],
            2 => ['Not impressed', 'Could be better', 'Some issues', 'Disappointing', 'Below expectations'],
            1 => ['Very disappointed', 'Many problems', 'Would not recommend', 'Poor quality', 'Big mistake'],
        ];

        return [
            'user_id' => \App\Models\User::factory(),
            'car_id' => \App\Models\Car::factory(),
            'order_id' => fake()->boolean(70) ? \App\Models\Order::factory() : null, // 70% have orders
            'rating' => $rating,
            'title' => fake()->randomElement($titles[$rating]),
            'comment' => fake()->paragraph(fake()->numberBetween(2, 5)),
            'is_verified_purchase' => fake()->boolean(70), // 70% verified
            'is_approved' => fake()->boolean(85), // 85% approved
            'helpful_count' => fake()->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the review is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    /**
     * Indicate that the review is from a verified purchase.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified_purchase' => true,
            'order_id' => \App\Models\Order::factory(),
        ]);
    }

    /**
     * Indicate that the review has a specific rating.
     */
    public function rating(int $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $rating,
        ]);
    }

    /**
     * Indicate that the review is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    /**
     * Indicate that the review has many helpful votes.
     */
    public function helpful(): static
    {
        return $this->state(fn (array $attributes) => [
            'helpful_count' => fake()->numberBetween(50, 200),
        ]);
    }
}
