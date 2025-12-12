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
        // All default cars belong to the platform owner
        $owner = User::where('email', 'owner@example.com')->first();
        
        if (!$owner) {
            $this->command->error('Owner user not found! Please run DealerSeeder first.');
            return;
        }

        // Get categories and conditions
        $hatchback = Category::where('slug', 'hatchback')->first();
        $suv = Category::where('slug', 'suv')->first();

        $new = Condition::where('slug', 'new')->first();
        $used = Condition::where('slug', 'used')->first();

        // Get Renault brand and its models
        $renault = Brand::where('name', 'Renault')->first();
        $linkCo = Brand::where('name', 'Lynk & Co')->first();
        $bmw = Brand::where('name', 'BMW')->first();
        $audi = Brand::where('name', 'Audi')->first();
        
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

        $bmwCars = [
            // Additional BMW cars can be defined here
            [
                'model_name' => 'X3',
                'year' => 2025,
                'condition' => $new,
                'category' => $suv,
                'price' => 36000,
                'mileage' => 5,
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'engine_size' => 2.0,
                'horsepower' => 228,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'Blue',
                'interior_color' => 'Beige',
                'description' => 'Brand new 2025 BMW X3 compact luxury SUV with sporty performance and premium features. Offers a dynamic driving experience, advanced technology, and a comfortable interior.',
                'image_folder' => '2025 BMW X3',
            ],
            [
                'model_name' => 'X7',
                'year' => 2023,
                'condition' => $new,
                'category' => $suv,
                'price' => 59000,
                'mileage' => 8,
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'engine_size' => 3.0,
                'horsepower' => 335,
                'doors' => 5,
                'seats' => 7,
                'exterior_color' => 'Black',
                'interior_color' => 'Black',
                'description' => 'New 2023 BMW X7 mid-size luxury SUV with powerful diesel engine and spacious 7-seat configuration. Features cutting-edge technology, luxurious interior, and exceptional driving dynamics.',
                'image_folder' => '2023 BMW X7',
            ],
            [
                'model_name' => 'i8 Coupe',
                'year' => 2019,
                'condition' => $used,
                'category' => $hatchback,
                'price' => 105000,
                'mileage' => 15000,
                'fuel_type' => 'hybrid',
                'transmission' => 'automatic',
                'engine_size' => 1.5,
                'horsepower' => 369,
                'doors' => 2,
                'seats' => 2,
                'exterior_color' => 'White',
                'interior_color' => 'Black',
                'description' => 'Sleek 2019 BMW i8 Coupe hybrid sports car with futuristic design and impressive performance. Combines electric and petrol power for an exhilarating driving experience with low emissions.',
                'image_folder' => '2019 BMW i8 Coupe',
            ],
            [
                'model_name' => '1 Series Sedan',
                'year' => 2017,
                'condition' => $used,
                'category' => $hatchback,
                'price' => 22000,
                'mileage' => 30000,
                'fuel_type' => 'petrol',
                'transmission' => 'manual',
                'engine_size' => 2.0,
                'horsepower' => 189,
                'doors' => 4,
                'seats' => 5,
                'exterior_color' => 'Silver',
                'interior_color' => 'Gray',
                'description' => 'Well-maintained 2017 BMW 1 Series Sedan with sporty handling and luxurious features. Perfect for those seeking a compact executive car with dynamic performance and premium comfort.',
                'image_folder' => '2017 BMW 1 Series Sedan',
            ],
            [
                'model_name' => '3 Series Touring',
                'year' => 2025,
                'condition' => $used,
                'category' => $hatchback,
                'price' => 28000,
                'mileage' => 40000,
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'engine_size' => 2.0,
                'horsepower' => 190,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'Blue',
                'interior_color' => 'Beige',
                'description' => 'Spacious 2025 BMW 3 Series Touring estate car with excellent cargo capacity and dynamic driving experience. Ideal for families needing versatility without compromising on performance.',
                'image_folder' => '2025 BMW 3 Series Touring',
            ],

        ];
        $audiCars = [
            // Additional Audi cars can be defined here
            [
                'model_name' => 'A3 Sedan',
                'year' => 2025,
                'condition' => $new,
                'category' => $hatchback,
                'price' => 33000,
                'mileage' => 12,
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'engine_size' => 2.0,
                'horsepower' => 190,
                'doors' => 4,
                'seats' => 5,
                'exterior_color' => 'Red',
                'interior_color' => 'Black',
                'description' => 'Brand new 2025 Audi A3 Sedan with sleek design and advanced technology. Offers a comfortable ride, premium interior, and efficient performance for everyday driving.',
                'image_folder' => '2025 Audi A3 Sedan',
            ],
            [
                'model_name' => 'Q6 e-tron offroad Concept',
                'year' => 2025,
                'condition' => $new,
                'category' => $suv,
                'price' => 75000,
                'mileage' => 7,
                'fuel_type' => 'electric',
                'transmission' => 'automatic',
                'engine_size' => 0.0,
                'horsepower' => 402,
                'doors' => 5,
                'seats' => 5,
                'exterior_color' => 'Gray',
                'interior_color' => 'White',
                'description' => 'Cutting-edge 2025 Audi Q6 e-tron offroad Concept electric SUV with futuristic design and powerful performance. Features advanced electric drivetrain, spacious interior, and innovative technology for an exceptional driving experience.',
                'image_folder' => '2025 Audi Q6 e-tron offroad Concept',
            ],
            [
                'model_name' => 'Q7',
                'year' => 2020,
                'condition' => $used,
                'category' => $suv,
                'price' => 68000,
                'mileage' => 20000,
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'engine_size' => 3.0,
                'horsepower' => 335,
                'doors' => 5,
                'seats' => 7,
                'exterior_color' => 'Black',
                'interior_color' => 'Brown',
                'description' => 'Luxurious 2020 Audi Q7 diesel SUV with spacious 7-seat configuration and advanced features. Combines powerful performance with refined comfort, perfect for families seeking versatility and style.',
                'image_folder' => '2020 Audi Q7',
            ],
            [
                'model_name' => 'Q7',
                'year' => 2025,
                'condition' => $new,
                'category' => $suv,
                'price' => 72000,
                'mileage' => 3,
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'engine_size' => 3.0,
                'horsepower' => 335,
                'doors' => 5,
                'seats' => 7,
                'exterior_color' => 'White',
                'interior_color' => 'Black',
                'description' => 'Brand new 2025 Audi Q7 petrol SUV with elegant design and cutting-edge technology. Offers a powerful engine, luxurious interior, and advanced safety features for an exceptional driving experience.',
                'image_folder' => '2025 Audi Q7',
            ],
            [
                'model_name' => 'A3 Cabriolet',
                'year' => 2017,
                'condition' => $used,
                'category' => $hatchback,
                'price' => 28000,
                'mileage' => 25000,
                'fuel_type' => 'petrol',
                'transmission' => 'manual',
                'engine_size' => 1.8,
                'horsepower' => 180,
                'doors' => 2,
                'seats' => 4,
                'exterior_color' => 'Silver',
                'interior_color' => 'Red',
                'description' => 'Stylish 2017 Audi A3 Cabriolet convertible with sporty performance and premium features. Perfect for enjoying open-top driving with a blend of comfort and agility.',
                'image_folder' => '2017 Audi A3 Cabriolet',
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
                'user_id' => $owner->id, // Owner user
                'dealer_id' => $owner->dealerProfile->id, // Owner's dealer profile
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
                'user_id' => $owner->id, // Owner user
                'dealer_id' => $owner->dealerProfile->id, // Owner's dealer profile
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

        foreach ($bmwCars as $index => $carData) {
            // Get the car model
            $model = CarModel::where('brand_id', $bmw->id)
                            ->where('name', $carData['model_name'])
                            ->first();

            if (!$model) {
                $this->command->warn("Model {$carData['model_name']} not found, skipping...");
                continue;
            }

            $cars[] = [
                'brand_id' => $bmw->id,
                'car_model_id' => $model->id,
                'category_id' => $carData['category']->id,
                'condition_id' => $carData['condition']->id,
                'user_id' => $owner->id, // Owner user
                'dealer_id' => $owner->dealerProfile->id, // Owner's dealer profile
                'title' => $carData['year'] . ' BMW ' . $carData['model_name'],
                'slug' => Str::slug($carData['year'] . '-bmw-' . $carData['model_name']),
                'description' => $carData['description'],
                'vin' => strtoupper(substr(md5('bmw-' . $carData['model_name'] . '-' . $carData['year']), 0, 17)),
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

        foreach ($audiCars as $index => $carData) {
            // Get the car model
            $model = CarModel::where('brand_id', $audi->id)
                            ->where('name', $carData['model_name'])
                            ->first();

            if (!$model) {
                $this->command->warn("Model {$carData['model_name']} not found, skipping...");
                continue;
            }

            $cars[] = [
                'brand_id' => $audi->id,
                'car_model_id' => $model->id,
                'category_id' => $carData['category']->id,
                'condition_id' => $carData['condition']->id,
                'user_id' => $owner->id, // Owner user
                'dealer_id' => $owner->dealerProfile->id, // Owner's dealer profile
                'title' => $carData['year'] . ' Audi ' . $carData['model_name'],
                'slug' => Str::slug($carData['year'] . '-audi-' . $carData['model_name']),
                'description' => $carData['description'],
                'vin' => strtoupper(substr(md5('audi-' . $carData['model_name'] . '-' . $carData['year']), 0, 17)),
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

        $this->command->info('Created ' . count($renaultCars) . ' Renault cars.');
        $this->command->info('Created ' . count($linkCoCars) . ' Lynk & Co cars.');
        $this->command->info('Created ' . count($bmwCars) . ' BMW cars.');
        $this->command->info('Created ' . count($audiCars) . ' Audi cars.');
    }
}