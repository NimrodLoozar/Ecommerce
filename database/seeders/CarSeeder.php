<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dealer = User::firstOrCreate(
            ['email' => 'dealer@example.com'],
            [
                'name' => 'Test Dealer',
                'password' => bcrypt('password'),
                'role' => 'dealer',
            ]
        );

        // Get categories and conditions
        $sedan = Category::where('slug', 'sedan')->first();
        $suv = Category::where('slug', 'suv')->first();
        $hatchback = Category::where('slug', 'hatchback')->first();
        $electric = Category::where('slug', 'electric')->first();

        $new = Condition::where('slug', 'new')->first();
        $used = Condition::where('slug', 'used')->first();
        $certified = Condition::where('slug', 'certified-pre-owned')->first();

        // Get car models with their brands
        $models = CarModel::with('brand')->get();

        $cars = [];

        foreach ($models->take(30) as $index => $model) {
            // Determine category based on model name/type
            $modelName = strtolower($model->name);
            $category = $sedan; // default

            if (str_contains($modelName, 'x') || str_contains($modelName, 'suv') || in_array($model->name, ['Captur', 'Tiguan', 'RAV4', 'Highlander', 'Q3', 'Q5', 'GLC', 'GLE', '3008', '5008', 'C5 Aircross'])) {
                $category = $suv;
            } elseif (in_array($model->name, ['Clio', 'Golf', '208', 'C3', 'C4'])) {
                $category = $hatchback;
            }

            // Randomize specs
            $year = rand(2018, 2024);
            $isNew = $year >= 2023;
            $condition = $isNew ? $new : ($index % 3 == 0 ? $certified : $used);

            $fuelTypes = ['petrol', 'diesel', 'electric', 'hybrid', 'plugin_hybrid'];
            $fuelType = $model->name === 'ID.4' || $model->name === 'iX' || $model->name === 'e-tron' || $model->name === 'EQC' || str_contains($model->name, 'Model')
                ? 'electric'
                : $fuelTypes[array_rand($fuelTypes)];

            $transmissions = ['manual', 'automatic', 'semi_automatic'];
            $transmission = $fuelType === 'electric' ? 'automatic' : $transmissions[array_rand($transmissions)];

            $colors = ['Black', 'White', 'Silver', 'Blue', 'Red', 'Gray', 'Green'];
            $exteriorColor = $colors[array_rand($colors)];
            $interiorColor = $colors[array_rand($colors)];            // Price based on brand, year, and condition
            $basePrice = match($model->brand->name) {
                'Mercedes-Benz', 'BMW', 'Audi', 'Porsche', 'Tesla' => rand(35000, 85000),
                'Volkswagen', 'Toyota', 'Mazda', 'Subaru', 'Volvo' => rand(20000, 45000),
                'Renault', 'Peugeot', 'Citroën' => rand(15000, 35000),
                default => rand(18000, 40000),
            };

            $yearFactor = ($year - 2018) / 6; // 0 to 1
            $price = round($basePrice * (0.5 + $yearFactor * 0.5), -2);

            $mileage = $isNew ? rand(10, 100) : rand(5000, 150000);

            $cars[] = [
                'brand_id' => $model->brand_id,
                'car_model_id' => $model->id,
                'category_id' => $category?->id ?? $sedan->id,
                'condition_id' => $condition?->id ?? $used->id,
                'user_id' => $dealer->id,
                'title' => $year . ' ' . $model->brand->name . ' ' . $model->name,
                'slug' => Str::slug($year . '-' . $model->brand->name . '-' . $model->name . '-' . $index),
                'description' => 'Well-maintained ' . $model->brand->name . ' ' . $model->name . ' with ' . $fuelType . ' engine. ' .
                               'Features include premium interior, advanced safety systems, and excellent fuel economy. ' .
                               'Perfect for city driving and long journeys.',
                'vin' => strtoupper(substr(md5($model->id . $index), 0, 17)),
                'year' => $year,
                'price' => $price,
                'mileage' => $mileage,
                'fuel_type' => $fuelType,
                'transmission' => $transmission,
                'engine_size' => $fuelType === 'electric' ? '0.0' : (rand(10, 30) / 10),
                'horsepower' => $fuelType === 'electric' ? rand(150, 400) : rand(90, 300),
                'doors' => rand(3, 5),
                'seats' => rand(4, 7),
                'exterior_color' => $exteriorColor,
                'interior_color' => $interiorColor,
                'stock_quantity' => rand(1, 5),
                'status' => 'available',
                'is_featured' => $index % 4 == 0, // 25% featured
                'views_count' => rand(10, 500),
                'created_at' => now()->subDays(rand(1, 90)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ];
        }

        foreach ($cars as $carData) {
            Car::create($carData);
        }

        $this->command->info('Created ' . count($cars) . ' cars.');
    }
}
