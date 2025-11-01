<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Sedan', 'description' => 'Four-door passenger cars with separate trunk', 'sort_order' => 1],
            ['name' => 'SUV', 'description' => 'Sport Utility Vehicles with high ground clearance', 'sort_order' => 2],
            ['name' => 'Coupe', 'description' => 'Two-door sporty cars with sleek design', 'sort_order' => 3],
            ['name' => 'Hatchback', 'description' => 'Compact cars with rear door and folding seats', 'sort_order' => 4],
            ['name' => 'Convertible', 'description' => 'Cars with retractable or removable roof', 'sort_order' => 5],
            ['name' => 'Wagon', 'description' => 'Extended sedans with more cargo space', 'sort_order' => 6],
            ['name' => 'Minivan', 'description' => 'Family vehicles with sliding doors and spacious interior', 'sort_order' => 7],
            ['name' => 'Pickup Truck', 'description' => 'Utility vehicles with open cargo bed', 'sort_order' => 8],
            ['name' => 'Sports Car', 'description' => 'High-performance vehicles built for speed', 'sort_order' => 9],
            ['name' => 'Luxury', 'description' => 'Premium vehicles with high-end features', 'sort_order' => 10],
            ['name' => 'Electric', 'description' => 'Fully electric zero-emission vehicles', 'sort_order' => 11],
            ['name' => 'Hybrid', 'description' => 'Vehicles combining electric and combustion engines', 'sort_order' => 12],
            ['name' => 'Compact', 'description' => 'Small efficient cars perfect for city driving', 'sort_order' => 13],
            ['name' => 'Crossover', 'description' => 'Blend of SUV and sedan characteristics', 'sort_order' => 14],
            ['name' => 'Van', 'description' => 'Commercial and passenger vans', 'sort_order' => 15],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => 'categories/' . Str::slug($category['name']) . '.jpg',
                'parent_id' => null,
                'is_active' => true,
                'sort_order' => $category['sort_order'],
            ]);
        }

        $this->command->info('Created ' . count($categories) . ' categories.');
    }
}
