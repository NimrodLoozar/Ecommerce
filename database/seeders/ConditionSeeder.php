<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conditions = [
            [
                'name' => 'New',
                'description' => 'Brand new vehicles with zero mileage, factory warranty, and latest features.',
            ],
            [
                'name' => 'Used',
                'description' => 'Pre-owned vehicles in various conditions, thoroughly inspected and verified.',
            ],
            [
                'name' => 'Certified Pre-Owned',
                'description' => 'Used vehicles meeting manufacturer standards with extended warranty and inspection.',
            ],
        ];

        foreach ($conditions as $condition) {
            Condition::create([
                'name' => $condition['name'],
                'slug' => Str::slug($condition['name']),
                'description' => $condition['description'],
            ]);
        }

        $this->command->info('Created ' . count($conditions) . ' conditions.');
    }
}
