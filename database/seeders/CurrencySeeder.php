<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 1.000000, // Base currency
                'is_default' => true,
                'is_active' => true,
                'decimals' => 2,
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.095000, // Approximate rate
                'is_default' => false,
                'is_active' => true,
                'decimals' => 2,
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'exchange_rate' => 0.865000, // Approximate rate
                'is_default' => false,
                'is_active' => true,
                'decimals' => 2,
            ],
            [
                'code' => 'CHF',
                'name' => 'Swiss Franc',
                'symbol' => 'CHF',
                'exchange_rate' => 0.950000, // Approximate rate
                'is_default' => false,
                'is_active' => true,
                'decimals' => 2,
            ],
            [
                'code' => 'SEK',
                'name' => 'Swedish Krona',
                'symbol' => 'kr',
                'exchange_rate' => 11.500000, // Approximate rate
                'is_default' => false,
                'is_active' => true,
                'decimals' => 2,
            ],
            [
                'code' => 'NOK',
                'name' => 'Norwegian Krone',
                'symbol' => 'kr',
                'exchange_rate' => 11.700000, // Approximate rate
                'is_default' => false,
                'is_active' => true,
                'decimals' => 2,
            ],
            [
                'code' => 'DKK',
                'name' => 'Danish Krone',
                'symbol' => 'kr',
                'exchange_rate' => 7.450000, // Approximate rate
                'is_default' => false,
                'is_active' => true,
                'decimals' => 2,
            ],
        ];

        foreach ($currencies as $currency) {
            \App\Models\Currency::create($currency);
        }
    }
}
