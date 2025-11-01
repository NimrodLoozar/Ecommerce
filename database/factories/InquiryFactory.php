<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inquiry>
 */
class InquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['new', 'in_progress', 'resolved', 'closed']);
        $hasResponse = in_array($status, ['resolved', 'closed']);

        $subjects = [
            'Question about this vehicle',
            'Financing options inquiry',
            'Trade-in valuation request',
            'Scheduling a test drive',
            'More information needed',
            'Availability question',
            'Delivery and shipping',
            'Vehicle history report',
            'Warranty information',
            'Price negotiation',
        ];

        return [
            'user_id' => fake()->boolean(60) ? \App\Models\User::factory() : null, // 60% registered users
            'car_id' => fake()->boolean(80) ? \App\Models\Car::factory() : null, // 80% car-specific
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->boolean(70) ? fake()->phoneNumber() : null,
            'subject' => fake()->randomElement($subjects),
            'message' => fake()->paragraph(fake()->numberBetween(2, 4)),
            'status' => $status,
            'admin_notes' => $status !== 'new' ? fake()->sentence(10) : null,
            'responded_at' => $hasResponse ? fake()->dateTimeBetween('-30 days', 'now') : null,
        ];
    }

    /**
     * Indicate that the inquiry is new.
     */
    public function statusNew(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'new',
            'admin_notes' => null,
            'responded_at' => null,
        ]);
    }

    /**
     * Indicate that the inquiry is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'admin_notes' => fake()->sentence(10),
            'responded_at' => null,
        ]);
    }

    /**
     * Indicate that the inquiry is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'admin_notes' => fake()->sentence(10),
            'responded_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the inquiry is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'admin_notes' => fake()->sentence(10),
            'responded_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the inquiry is for a specific car.
     */
    public function forCar(int $carId): static
    {
        return $this->state(fn (array $attributes) => [
            'car_id' => $carId,
        ]);
    }

    /**
     * Indicate that the inquiry is from a guest (no user account).
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }
}
