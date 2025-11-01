<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestDrive>
 */
class TestDriveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']);
        $isConfirmed = in_array($status, ['confirmed', 'completed']);
        $preferredDate = fake()->dateTimeBetween('now', '+30 days');

        // Time slots during business hours
        $timeSlots = ['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

        return [
            'user_id' => fake()->boolean(70) ? \App\Models\User::factory() : null, // 70% registered users
            'car_id' => \App\Models\Car::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'preferred_date' => $preferredDate->format('Y-m-d'),
            'preferred_time' => fake()->randomElement($timeSlots),
            'message' => fake()->boolean(50) ? fake()->sentence(15) : null,
            'status' => $status,
            'confirmed_date' => $isConfirmed ? fake()->dateTimeBetween($preferredDate, '+35 days') : null,
            'admin_notes' => $status !== 'pending' ? fake()->sentence(10) : null,
        ];
    }

    /**
     * Indicate that the test drive is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'confirmed_date' => null,
            'admin_notes' => null,
        ]);
    }

    /**
     * Indicate that the test drive is confirmed.
     */
    public function confirmed(): static
    {
        $preferredDate = fake()->dateTimeBetween('now', '+30 days');

        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'preferred_date' => $preferredDate->format('Y-m-d'),
            'confirmed_date' => fake()->dateTimeBetween($preferredDate, '+35 days'),
            'admin_notes' => 'Confirmed with customer via phone',
        ]);
    }

    /**
     * Indicate that the test drive is completed.
     */
    public function completed(): static
    {
        $preferredDate = fake()->dateTimeBetween('-30 days', '-1 day');

        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'preferred_date' => $preferredDate->format('Y-m-d'),
            'confirmed_date' => fake()->dateTimeBetween($preferredDate, $preferredDate->modify('+5 days')),
            'admin_notes' => 'Test drive completed successfully',
        ]);
    }

    /**
     * Indicate that the test drive is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'confirmed_date' => null,
            'admin_notes' => fake()->randomElement([
                'Customer cancelled',
                'Car no longer available',
                'Rescheduled to different date',
                'Customer did not show up',
            ]),
        ]);
    }

    /**
     * Indicate that the test drive is for a specific car.
     */
    public function forCar(int $carId): static
    {
        return $this->state(fn (array $attributes) => [
            'car_id' => $carId,
        ]);
    }

    /**
     * Indicate that the test drive is from a guest (no user account).
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    /**
     * Indicate that the test drive is scheduled for today.
     */
    public function today(): static
    {
        $timeSlots = ['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];

        return $this->state(fn (array $attributes) => [
            'preferred_date' => now()->format('Y-m-d'),
            'preferred_time' => fake()->randomElement($timeSlots),
            'status' => 'confirmed',
            'confirmed_date' => now(),
        ]);
    }
}
