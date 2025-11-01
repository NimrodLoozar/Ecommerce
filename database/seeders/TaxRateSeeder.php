<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder adds country-level VAT rates
        // Most European countries have uniform VAT rates, which are already set in the countries table
        // This seeder can be used to add state/region-specific rates if needed in the future

        $countries = \App\Models\Country::all();

        foreach ($countries as $country) {
            \App\Models\TaxRate::create([
                'country_id' => $country->id,
                'state' => null, // Country-level rate
                'rate' => $country->tax_rate,
                'name' => 'VAT', // Value Added Tax
                'is_active' => true,
            ]);
        }

        // Example: Add reduced VAT rates for specific countries (optional)
        $nlId = \App\Models\Country::where('code', 'NL')->first()->id;

        // Netherlands reduced rates
        \App\Models\TaxRate::create([
            'country_id' => $nlId,
            'state' => null,
            'rate' => 9.00, // Reduced rate for certain goods
            'name' => 'VAT (Reduced)',
            'is_active' => true,
        ]);
    }
}
