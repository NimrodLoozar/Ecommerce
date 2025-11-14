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
        $linkCo = Brand::where('name', 'Lynk & Co')->first();
        
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
                'year' => 2025,
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
                'model_name' => 'Megane E-Tech',
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
                'model_name' => 'Grand Scenic',
                'year' => 2010,
                'condition' => $used,
                'category' => $suv,
                'price' => 8000,
                'mileage' => 90000,
                'fuel_type' => 'petrol',
                'transmission' => 'manual',
                'engine_size' => 1.6,
                'horsepower' => 110,
                'doors' => 5,
                'seats' => 7,
                'exterior_color' => 'Red',
                'interior_color' => 'Beige',
                'description' => 'Spacious 2010 Renault Grand Scenic with 7 seats, perfect for families. Reliable petrol engine with ample cargo space and comfortable interior. Great value for a practical family SUV.',
                 'image_folder' => '2010 Renault Grand Scenic',
            ],
            [
                'model_name' => 'Twizy',
                'year' => 2012,
                'condition' => $used,
                'category' => $hatchback,
                'price' => 6000,
                'mileage' => 15000,
                'fuel_type' => 'electric',
                'transmission' => 'automatic',
                'engine_size' => 0.0,
                'horsepower' => 17,
                'doors' => 2,
                'seats' => 2,
                'exterior_color' => 'Yellow',
                'interior_color' => 'Black',
                'description' => 'Compact 2012 Renault Twizy electric city car, ideal for urban commuting. Easy to park and maneuver with zero emissions. Fun and economical way to get around the city.',
                'image_folder' => '2012 Renault Twizy',
            ],
            [
                'model_name' => 'Laguna Estate',
                'year' => 2011,
                'condition' => $used,
                'category' => $suv,
                'price' => 7500,
                'mileage' => 85000,
                'fuel_type' => 'diesel',
                'transmission' => 'manual',
                'engine_size' => 2.0,
                'horsepower' => 150,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'Green',
                'interior_color' => 'Black',
                'description' => 'Reliable 2011 Renault Laguna Estate diesel with spacious cargo area. Perfect for families needing extra space. Comfortable ride with good fuel efficiency and solid performance.',
                'image_folder' => '2011 Renault Laguna Estate',
            ],
        ];

        $linkCoCars = [
            [
                'model_name' => '01',
                'year' => 2018,
                'condition' => $new,
                'category' => $suv,
                'price' => 32000,
                'mileage' => 10,
                'fuel_type' => 'plugin_hybrid',
                'transmission' => 'automatic',
                'engine_size' => 1.5,
                'horsepower' => 180,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'Black',
                'interior_color' => 'Gray',
                'description' => 'Brand new 2018 Lynk & Co 01 plug-in hybrid SUV with cutting-edge technology and stylish design. Offers a comfortable ride, advanced safety features, and eco-friendly driving experience.',
                'image_folder' => '2018 Lynk & Co 01',
            ],
            [
                'model_name' => '01 Update',
                'year' => 2019,
                'condition' => $used,
                'category' => $suv,
                'price' => 28000,
                'mileage' => 20000,
                'fuel_type' => 'hybrid',
                'transmission' => 'automatic',
                'engine_size' => 1.5,
                'horsepower' => 160,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'White',
                'interior_color' => 'Black',
                'description' => 'Well-maintained 2019 Lynk & Co 01 Update hybrid SUV with low mileage. Combines efficiency with performance, featuring a spacious interior and modern infotainment system.',
                'image_folder' => '2019 Lynk & Co 01 Update',
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

        foreach ($linkCoCars as $index => $carData) {
            // Get the car model
            $model = CarModel::where('brand_id', $linkCo->id)
                            ->where('name', $carData['model_name'])
                            ->first();

            if (!$model) {
                $this->command->warn("Model {$carData['model_name']} not found, skipping...");
                continue;
            }

            $cars[] = [
                'brand_id' => $linkCo->id,
                'car_model_id' => $model->id,
                'category_id' => $carData['category']->id,
                'condition_id' => $carData['condition']->id,
                'user_id' => $dealer->id,
                'title' => $carData['year'] . ' Lynk & Co ' . $carData['model_name'],
                'slug' => Str::slug($carData['year'] . '-lynk-co-' . $carData['model_name']),
                'description' => $carData['description'],
                'vin' => strtoupper(substr(md5('lynkco-' . $carData['model_name'] . '-' . $carData['year']), 0, 17)),
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
                'is_featured' => $index < 1, // First 1 is featured
                'views_count' => rand(50, 300),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 15)),
            ];
        }

        foreach ($cars as $carData) {
            Car::create($carData);
        }

        $this->command->info('Created ' . count($renaultCars) . ' Renault cars.');
        $this->command->info('Created ' . count($linkCoCars) . ' Lynk & Co cars.');
    }
}