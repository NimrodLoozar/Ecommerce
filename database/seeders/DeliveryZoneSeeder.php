<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get country IDs for zones
        $nlId = \App\Models\Country::where('code', 'NL')->first()->id;
        $beId = \App\Models\Country::where('code', 'BE')->first()->id;
        $luId = \App\Models\Country::where('code', 'LU')->first()->id;
        $deId = \App\Models\Country::where('code', 'DE')->first()->id;
        $frId = \App\Models\Country::where('code', 'FR')->first()->id;
        $atId = \App\Models\Country::where('code', 'AT')->first()->id;
        $itId = \App\Models\Country::where('code', 'IT')->first()->id;
        $esId = \App\Models\Country::where('code', 'ES')->first()->id;
        $ptId = \App\Models\Country::where('code', 'PT')->first()->id;
        $ieId = \App\Models\Country::where('code', 'IE')->first()->id;
        $gbId = \App\Models\Country::where('code', 'GB')->first()->id;
        $chId = \App\Models\Country::where('code', 'CH')->first()->id;
        $seId = \App\Models\Country::where('code', 'SE')->first()->id;
        $noId = \App\Models\Country::where('code', 'NO')->first()->id;
        $dkId = \App\Models\Country::where('code', 'DK')->first()->id;
        $fiId = \App\Models\Country::where('code', 'FI')->first()->id;
        $plId = \App\Models\Country::where('code', 'PL')->first()->id;
        $czId = \App\Models\Country::where('code', 'CZ')->first()->id;
        $grId = \App\Models\Country::where('code', 'GR')->first()->id;
        $huId = \App\Models\Country::where('code', 'HU')->first()->id;

        $zones = [
            [
                'name' => 'Benelux (Domestic)',
                'countries' => [$nlId, $beId, $luId],
                'delivery_fee' => 0.00,
                'free_delivery_threshold' => null, // Always free
                'estimated_days_min' => 1,
                'estimated_days_max' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'EU Zone 1 (Neighbors)',
                'countries' => [$deId, $frId, $atId],
                'delivery_fee' => 500.00,
                'free_delivery_threshold' => 30000.00, // Free over €30k
                'estimated_days_min' => 2,
                'estimated_days_max' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'EU Zone 2 (South)',
                'countries' => [$itId, $esId, $ptId, $grId],
                'delivery_fee' => 800.00,
                'free_delivery_threshold' => 35000.00, // Free over €35k
                'estimated_days_min' => 3,
                'estimated_days_max' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'EU Zone 3 (East)',
                'countries' => [$plId, $czId, $huId],
                'delivery_fee' => 700.00,
                'free_delivery_threshold' => 35000.00,
                'estimated_days_min' => 4,
                'estimated_days_max' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Nordic Countries',
                'countries' => [$seId, $noId, $dkId, $fiId],
                'delivery_fee' => 900.00,
                'free_delivery_threshold' => 40000.00,
                'estimated_days_min' => 3,
                'estimated_days_max' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'United Kingdom',
                'countries' => [$gbId],
                'delivery_fee' => 1200.00,
                'free_delivery_threshold' => 45000.00,
                'estimated_days_min' => 5,
                'estimated_days_max' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Switzerland',
                'countries' => [$chId],
                'delivery_fee' => 1000.00,
                'free_delivery_threshold' => 40000.00,
                'estimated_days_min' => 4,
                'estimated_days_max' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Ireland',
                'countries' => [$ieId],
                'delivery_fee' => 1500.00,
                'free_delivery_threshold' => 50000.00,
                'estimated_days_min' => 7,
                'estimated_days_max' => 14,
                'is_active' => true,
            ],
        ];

        foreach ($zones as $zone) {
            \App\Models\DeliveryZone::create($zone);
        }
    }
}
