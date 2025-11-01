<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            // Renault (ID: 1)
            ['brand_id' => 1, 'name' => 'Clio'],
            ['brand_id' => 1, 'name' => 'Megane'],
            ['brand_id' => 1, 'name' => 'Captur'],
            ['brand_id' => 1, 'name' => 'Scenic'],

            // BMW (ID: 2)
            ['brand_id' => 2, 'name' => '3 Series'],
            ['brand_id' => 2, 'name' => '5 Series'],
            ['brand_id' => 2, 'name' => 'X3'],
            ['brand_id' => 2, 'name' => 'X5'],
            ['brand_id' => 2, 'name' => 'iX'],

            // Audi (ID: 3)
            ['brand_id' => 3, 'name' => 'A3'],
            ['brand_id' => 3, 'name' => 'A4'],
            ['brand_id' => 3, 'name' => 'Q3'],
            ['brand_id' => 3, 'name' => 'Q5'],
            ['brand_id' => 3, 'name' => 'e-tron'],

            // Peugeot (ID: 4)
            ['brand_id' => 4, 'name' => '208'],
            ['brand_id' => 4, 'name' => '308'],
            ['brand_id' => 4, 'name' => '3008'],
            ['brand_id' => 4, 'name' => '5008'],

            // Mercedes-Benz (ID: 5)
            ['brand_id' => 5, 'name' => 'C-Class'],
            ['brand_id' => 5, 'name' => 'E-Class'],
            ['brand_id' => 5, 'name' => 'GLC'],
            ['brand_id' => 5, 'name' => 'GLE'],
            ['brand_id' => 5, 'name' => 'EQC'],

            // Lynk & Co (ID: 6)
            ['brand_id' => 6, 'name' => '01'],
            ['brand_id' => 6, 'name' => '02'],
            ['brand_id' => 6, 'name' => '03'],

            // Citroën (ID: 7)
            ['brand_id' => 7, 'name' => 'C3'],
            ['brand_id' => 7, 'name' => 'C4'],
            ['brand_id' => 7, 'name' => 'C5 Aircross'],

            // Volkswagen (ID: 8)
            ['brand_id' => 8, 'name' => 'Golf'],
            ['brand_id' => 8, 'name' => 'Passat'],
            ['brand_id' => 8, 'name' => 'Tiguan'],
            ['brand_id' => 8, 'name' => 'ID.4'],

            // Toyota (ID: 9)
            ['brand_id' => 9, 'name' => 'Corolla'],
            ['brand_id' => 9, 'name' => 'Camry'],
            ['brand_id' => 9, 'name' => 'RAV4'],
            ['brand_id' => 9, 'name' => 'Highlander'],

            // Tesla (ID: 21)
            ['brand_id' => 21, 'name' => 'Model 3'],
            ['brand_id' => 21, 'name' => 'Model Y'],
            ['brand_id' => 21, 'name' => 'Model S'],
            ['brand_id' => 21, 'name' => 'Model X'],
        ];

        foreach ($models as $model) {
            CarModel::create([
                'brand_id' => $model['brand_id'],
                'name' => $model['name'],
                'slug' => Str::slug($model['name']),
                'description' => 'The ' . $model['name'] . ' is a popular model featuring modern design and reliable performance.',
                'is_active' => true,
            ]);
        }

        $this->command->info('Created ' . count($models) . ' car models.');
    }
}
