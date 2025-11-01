<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DealerProfile>
 */
class DealerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyTypes = ['GmbH', 'AG', 'BV', 'SA', 'Ltd', 'SRL', 'SL', 'AB'];
        $businessNames = [
            'AutoHaus', 'Premium Motors', 'Elite Cars', 'City Automotive',
            'DriveStyle', 'Luxury Wheels', 'Best Auto', 'TopCar', 'CarWorld',
            'Perfect Drive', 'Auto Excellence', 'Motor Group', 'Car Center'
        ];

        $companyName = fake()->randomElement($businessNames) . ' ' . fake()->randomElement($companyTypes);

        return [
            'user_id' => User::factory()->dealer(),
            'company_name' => $companyName,
            'business_registration' => strtoupper(fake()->bothify('??-####-###')),
            'tax_id' => fake()->numerify('EU########'),
            'logo' => 'logos/' . fake()->slug(2) . '.png',
            'description' => fake()->paragraph(3),
            'phone' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'commission_rate' => fake()->randomFloat(2, 3.00, 15.00),
            'subscription_plan' => fake()->randomElement(['basic', 'premium', 'enterprise']),
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
            'bank_account' => fake()->iban('NL'),
            'documents' => [
                'business_license' => 'documents/business-license-' . fake()->uuid() . '.pdf',
                'tax_certificate' => 'documents/tax-cert-' . fake()->uuid() . '.pdf',
                'insurance' => 'documents/insurance-' . fake()->uuid() . '.pdf',
            ],
        ];
    }

    /**
     * Indicate that the dealer profile is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => User::factory()->admin(),
            'approved_at' => now()->subDays(rand(1, 30)),
        ]);
    }

    /**
     * Indicate that the dealer profile is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
            'approved_by' => User::factory()->admin(),
            'approved_at' => now()->subDays(rand(60, 120)),
        ]);
    }

    /**
     * Indicate that the dealer profile is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'approved_by' => User::factory()->admin(),
            'approved_at' => now()->subDays(rand(1, 10)),
        ]);
    }

    /**
     * Indicate that the dealer has a premium subscription.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'subscription_plan' => 'premium',
            'commission_rate' => fake()->randomFloat(2, 5.00, 10.00),
        ]);
    }

    /**
     * Indicate that the dealer has an enterprise subscription.
     */
    public function enterprise(): static
    {
        return $this->state(fn (array $attributes) => [
            'subscription_plan' => 'enterprise',
            'commission_rate' => fake()->randomFloat(2, 3.00, 7.00),
        ]);
    }
}
