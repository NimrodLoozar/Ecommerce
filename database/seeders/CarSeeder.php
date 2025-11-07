<?php

namespace Database\Seeders;

use App\Models\Brand;
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
        $hatchback = Category::where('slug', 'hatchback')->first();
        $suv = Category::where('slug', 'suv')->first();

        $new = Condition::where('slug', 'new')->first();
        $used = Condition::where('slug', 'used')->first();

        // Get Renault brand and its models
        $renault = Brand::where('name', 'Renault')->first();
        
        // Define the 5 specific Renault cars based on folder structure
        $renaultCars = [
            [
                'model_name' => 'Clio',
                'year' => 2024,
                'condition' => $new,
                'category' => $hatchback,
                'price' => 24500,
                'mileage' => 15,
                'fuel_type' => 'petrol',
                'transmission' => 'manual',
                'engine_size' => 1.0,
                'horsepower' => 90,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'Blue',
                'interior_color' => 'Black',
                'description' => 'Brand new 2024 Renault Clio with modern design and excellent fuel economy. Perfect city car with advanced safety features and comfortable interior. Ideal for urban driving with responsive handling.',
                'image_folder' => '2024 Renault Clio',
            ],
            [
                'model_name' => 'Captur',
                'year' => 2024,
                'condition' => $new,
                'category' => $suv,
                'price' => 28900,
                'mileage' => 50,
                'fuel_type' => 'hybrid',
                'transmission' => 'automatic',
                'engine_size' => 1.6,
                'horsepower' => 145,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'Orange',
                'interior_color' => 'Gray',
                'description' => 'New 2024 Renault Captur hybrid SUV with spacious interior and modern technology. Features efficient hybrid powertrain, elevated driving position, and versatile cargo space.',
                'image_folder' => '2024 Renault Captur',
            ],
            [
                'model_name' => 'Megane',
                'year' => 2022,
                'condition' => $used,
                'category' => $hatchback,
                'price' => 32500,
                'mileage' => 12000,
                'fuel_type' => 'electric',
                'transmission' => 'automatic',
                'engine_size' => 0.0,
                'horsepower' => 217,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'White',
                'interior_color' => 'Black',
                'description' => '2022 Renault Megane E-Tech electric with low mileage. Fully electric with impressive range, advanced driver assistance systems, and premium interior. Environmentally friendly with zero emissions.',
                'image_folder' => '2022 Renault Megane E-Tech',
            ],
            [
                'model_name' => 'Megane',
                'year' => 2019,
                'condition' => $used,
                'category' => $hatchback,
                'price' => 16500,
                'mileage' => 45000,
                'fuel_type' => 'diesel',
                'transmission' => 'manual',
                'engine_size' => 1.5,
                'horsepower' => 115,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'Silver',
                'interior_color' => 'Gray',
                'description' => 'Well-maintained 2019 Renault Megane diesel with excellent fuel economy. Reliable and efficient with comfortable ride quality. Great value family hatchback with good service history.',
                'image_folder' => '2019 Renault Megan',
            ],
            [
                'model_name' => 'Scenic',
                'year' => 2018,
                'condition' => $used,
                'category' => $suv,
                'price' => 14900,
                'mileage' => 68000,
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'engine_size' => 1.3,
                'horsepower' => 140,
                'doors' => 5,
                'seats' => 7,
                'exterior_color' => 'Black',
                'interior_color' => 'Beige',
                'description' => '2018 Renault Scenic 7-seater MPV with spacious interior. Perfect family car with practical seating arrangement, ample storage space, and comfortable ride for long journeys.',
                'image_folder' => '2018 Renault Scenic',
            ],
        ];

        $cars = [];

        foreach ($renaultCars as $index => $carData) {
            // Get the car model
            $model = CarModel::where('brand_id', $renault->id)
                            ->where('name', $carData['model_name'])
                            ->first();

            if (!$model) {
                $this->command->warn("Model {$carData['model_name']} not found, skipping...");
                continue;
            }

            $cars[] = [
                'brand_id' => $renault->id,
                'car_model_id' => $model->id,
                'category_id' => $carData['category']->id,
                'condition_id' => $carData['condition']->id,
                'user_id' => $dealer->id,
                'title' => $carData['year'] . ' Renault ' . $carData['model_name'],
                'slug' => Str::slug($carData['year'] . '-renault-' . $carData['model_name']),
                'description' => $carData['description'],
                'vin' => strtoupper(substr(md5('renault-' . $carData['model_name'] . '-' . $carData['year']), 0, 17)),
                'year' => $carData['year'],
                'price' => $carData['price'],
                'mileage' => $carData['mileage'],
                'fuel_type' => $carData['fuel_type'],
                'transmission' => $carData['transmission'],
                'engine_size' => $carData['engine_size'],
                'horsepower' => $carData['horsepower'],
                'doors' => $carData['doors'],
                'seats' => $carData['seats'],
                'exterior_color' => $carData['exterior_color'],
                'interior_color' => $carData['interior_color'],
                'stock_quantity' => 1,
                'status' => 'available',
                'is_featured' => $index < 2, // First 2 are featured
                'views_count' => rand(50, 300),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 15)),
            ];
        }

        foreach ($cars as $carData) {
            Car::create($carData);
        }

        $this->command->info('Created ' . count($cars) . ' Renault cars.');
    }
}