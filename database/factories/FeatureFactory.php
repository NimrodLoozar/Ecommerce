<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feature>
 */
class FeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $features = [
            // Safety
            ['name' => 'ABS Brakes', 'category' => 'safety', 'icon' => 'shield-check'],
            ['name' => 'Airbags', 'category' => 'safety', 'icon' => 'shield'],
            ['name' => 'Backup Camera', 'category' => 'safety', 'icon' => 'camera'],
            ['name' => 'Blind Spot Monitoring', 'category' => 'safety', 'icon' => 'eye'],
            ['name' => 'Lane Departure Warning', 'category' => 'safety', 'icon' => 'alert-triangle'],

            // Comfort
            ['name' => 'Leather Seats', 'category' => 'comfort', 'icon' => 'armchair'],
            ['name' => 'Heated Seats', 'category' => 'comfort', 'icon' => 'thermometer'],
            ['name' => 'Ventilated Seats', 'category' => 'comfort', 'icon' => 'wind'],
            ['name' => 'Sunroof', 'category' => 'comfort', 'icon' => 'sun'],
            ['name' => 'Climate Control', 'category' => 'comfort', 'icon' => 'snowflake'],

            // Technology
            ['name' => 'GPS Navigation', 'category' => 'technology', 'icon' => 'map'],
            ['name' => 'Bluetooth', 'category' => 'technology', 'icon' => 'bluetooth'],
            ['name' => 'Apple CarPlay', 'category' => 'technology', 'icon' => 'smartphone'],
            ['name' => 'Android Auto', 'category' => 'technology', 'icon' => 'smartphone'],
            ['name' => 'Premium Sound System', 'category' => 'technology', 'icon' => 'music'],

            // Performance
            ['name' => 'All-Wheel Drive', 'category' => 'performance', 'icon' => 'settings'],
            ['name' => 'Sport Mode', 'category' => 'performance', 'icon' => 'zap'],
            ['name' => 'Adaptive Suspension', 'category' => 'performance', 'icon' => 'activity'],
        ];

        $feature = fake()->unique()->randomElement($features);

        return [
            'name' => $feature['name'],
            'slug' => Str::slug($feature['name']),
            'icon' => $feature['icon'],
            'category' => $feature['category'],
        ];
    }

    /**
     * Indicate that the feature is a safety feature.
     */
    public function safety(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'safety',
        ]);
    }

    /**
     * Indicate that the feature is a comfort feature.
     */
    public function comfort(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'comfort',
        ]);
    }
}
