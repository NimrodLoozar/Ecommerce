<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            // Brands with actual images in public/img/
            ['name' => 'Renault', 'image' => 'img/Renault/renault.webp', 'sort_order' => 1],
            ['name' => 'BMW', 'image' => 'img/BMW/BMW.webp', 'sort_order' => 2],
            ['name' => 'Audi', 'image' => 'img/Audi/audi.avif', 'sort_order' => 3],
            ['name' => 'Peugeot', 'image' => 'img/Peugeot/peugeot.jpg', 'sort_order' => 4],
            ['name' => 'Mercedes-Benz', 'image' => 'img/Mercedes-Benz/mercedes.avif', 'sort_order' => 5],
            ['name' => 'Lynk & Co', 'image' => 'img/Lynk&Co/link&co.jpg', 'sort_order' => 6],
            ['name' => 'Citroën', 'image' => 'img/Citroën/citroen.avif', 'sort_order' => 7],
            ['name' => 'BYD', 'image' => 'img/BYD/byd.jpg', 'sort_order' => 8],

            // Other brands without specific images (will use logo from svg folder or default)
            ['name' => 'Volkswagen', 'image' => null, 'sort_order' => 8],
            ['name' => 'Toyota', 'image' => null, 'sort_order' => 9],
            ['name' => 'Honda', 'image' => null, 'sort_order' => 10],
            ['name' => 'Ford', 'image' => null, 'sort_order' => 11],
            ['name' => 'Chevrolet', 'image' => null, 'sort_order' => 12],
            ['name' => 'Nissan', 'image' => null, 'sort_order' => 13],
            ['name' => 'Hyundai', 'image' => null, 'sort_order' => 14],
            ['name' => 'Kia', 'image' => null, 'sort_order' => 15],
            ['name' => 'Mazda', 'image' => null, 'sort_order' => 16],
            ['name' => 'Subaru', 'image' => null, 'sort_order' => 17],
            ['name' => 'Volvo', 'image' => null, 'sort_order' => 18],
            ['name' => 'Lexus', 'image' => null, 'sort_order' => 19],
            ['name' => 'Porsche', 'image' => null, 'sort_order' => 20],
            ['name' => 'Tesla', 'image' => null, 'sort_order' => 21],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'logo' => 'brands/' . Str::slug($brand['name']) . '.png',
                'image' => $brand['image'], // Category/showcase image
                'description' => 'Premium ' . $brand['name'] . ' vehicles with quality and performance.',
                'is_active' => true,
                'sort_order' => $brand['sort_order'],
            ]);
        }

        $this->command->info('Created ' . count($brands) . ' brands.');
    }
}