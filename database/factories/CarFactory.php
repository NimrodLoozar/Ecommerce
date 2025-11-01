<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->numberBetween(2015, 2025);
        $isNew = $year >= 2024;
        $mileage = $isNew ? fake()->numberBetween(0, 100) : fake()->numberBetween(5000, 200000);
        $price = fake()->numberBetween(15000, 80000);

        $brand = Brand::factory()->create();
        $model = CarModel::factory()->create(['brand_id' => $brand->id]);
        $title = $year . ' ' . $brand->name . ' ' . $model->name;

        return [
            'brand_id' => $brand->id,
            'car_model_id' => $model->id,
            'category_id' => Category::factory(),
            'condition_id' => Condition::factory(),
            'user_id' => User::factory(),
            'vin' => strtoupper(fake()->unique()->bothify('??#############')),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->paragraphs(3, true),
            'year' => $year,
            'mileage' => $mileage,
            'price' => $price,
            'lease_price_monthly' => fake()->boolean(70) ? round($price * 0.015, 2) : null,
            'fuel_type' => fake()->randomElement(['petrol', 'diesel', 'electric', 'hybrid', 'plugin_hybrid']),
            'transmission' => fake()->randomElement(['manual', 'automatic', 'semi_automatic']),
            'engine_size' => fake()->randomFloat(1, 1.0, 5.0),
            'horsepower' => fake()->numberBetween(100, 500),
            'exterior_color' => fake()->randomElement(['Black', 'White', 'Silver', 'Gray', 'Blue', 'Red', 'Green', 'Yellow']),
            'interior_color' => fake()->randomElement(['Black', 'Beige', 'Gray', 'Brown']),
            'doors' => fake()->randomElement([2, 4, 5]),
            'seats' => fake()->randomElement([2, 4, 5, 7]),
            'stock_quantity' => fake()->numberBetween(1, 5),
            'status' => fake()->randomElement(['available', 'available', 'available', 'reserved', 'sold']),
            'is_featured' => fake()->boolean(20),
            'views_count' => fake()->numberBetween(0, 1000),
            'meta_title' => $title . ' - For Sale',
            'meta_description' => fake()->sentence(),
        ];
    }

    /**
     * Indicate that the car is available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
            'stock_quantity' => fake()->numberBetween(1, 5),
        ]);
    }

    /**
     * Indicate that the car is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the car is brand new.
     */
    public function brandNew(): static
    {
        return $this->state(fn (array $attributes) => [
            'year' => fake()->numberBetween(2024, 2025),
            'mileage' => fake()->numberBetween(0, 100),
        ]);
    }

    /**
     * Indicate that the car is used.
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'year' => fake()->numberBetween(2015, 2023),
            'mileage' => fake()->numberBetween(10000, 150000),
        ]);
    }

    /**
     * Indicate that the car is electric.
     */
    public function electric(): static
    {
        return $this->state(fn (array $attributes) => [
            'fuel_type' => 'electric',
            'engine_size' => null,
        ]);
    }
}
