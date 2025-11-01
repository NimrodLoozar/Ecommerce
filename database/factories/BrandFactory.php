<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Audi', 'BMW', 'Mercedes-Benz', 'Volkswagen', 'Toyota',
            'Honda', 'Ford', 'Chevrolet', 'Nissan', 'Hyundai',
            'Kia', 'Mazda', 'Subaru', 'Volvo', 'Lexus',
            'Porsche', 'Tesla', 'Citroën', 'Peugeot', 'Renault',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'logo' => 'brands/' . Str::slug($name) . '.png',
            'description' => fake()->paragraph(),
            'is_active' => fake()->boolean(90), // 90% active
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the brand is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the brand is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
