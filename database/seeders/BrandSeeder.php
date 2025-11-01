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
            ['name' => 'Audi', 'sort_order' => 1],
            ['name' => 'BMW', 'sort_order' => 2],
            ['name' => 'Mercedes-Benz', 'sort_order' => 3],
            ['name' => 'Volkswagen', 'sort_order' => 4],
            ['name' => 'Toyota', 'sort_order' => 5],
            ['name' => 'Honda', 'sort_order' => 6],
            ['name' => 'Ford', 'sort_order' => 7],
            ['name' => 'Chevrolet', 'sort_order' => 8],
            ['name' => 'Nissan', 'sort_order' => 9],
            ['name' => 'Hyundai', 'sort_order' => 10],
            ['name' => 'Kia', 'sort_order' => 11],
            ['name' => 'Mazda', 'sort_order' => 12],
            ['name' => 'Subaru', 'sort_order' => 13],
            ['name' => 'Volvo', 'sort_order' => 14],
            ['name' => 'Lexus', 'sort_order' => 15],
            ['name' => 'Porsche', 'sort_order' => 16],
            ['name' => 'Tesla', 'sort_order' => 17],
            ['name' => 'Citroën', 'sort_order' => 18],
            ['name' => 'Peugeot', 'sort_order' => 19],
            ['name' => 'Renault', 'sort_order' => 20],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'logo' => 'brands/' . Str::slug($brand['name']) . '.png',
                'description' => 'Premium ' . $brand['name'] . ' vehicles with quality and performance.',
                'is_active' => true,
                'sort_order' => $brand['sort_order'],
            ]);
        }

        $this->command->info('Created ' . count($brands) . ' brands.');
    }
}
