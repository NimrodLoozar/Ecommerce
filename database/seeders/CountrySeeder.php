<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eur = \App\Models\Currency::where('code', 'EUR')->first()->id;
        $gbp = \App\Models\Currency::where('code', 'GBP')->first()->id;
        $chf = \App\Models\Currency::where('code', 'CHF')->first()->id;
        $sek = \App\Models\Currency::where('code', 'SEK')->first()->id;
        $nok = \App\Models\Currency::where('code', 'NOK')->first()->id;
        $dkk = \App\Models\Currency::where('code', 'DKK')->first()->id;

        $countries = [
            ['code' => 'NL', 'name' => 'Netherlands', 'currency_id' => $eur, 'tax_rate' => 21.00, 'phone_code' => '+31', 'sort_order' => 1],
            ['code' => 'DE', 'name' => 'Germany', 'currency_id' => $eur, 'tax_rate' => 19.00, 'phone_code' => '+49', 'sort_order' => 2],
            ['code' => 'BE', 'name' => 'Belgium', 'currency_id' => $eur, 'tax_rate' => 21.00, 'phone_code' => '+32', 'sort_order' => 3],
            ['code' => 'FR', 'name' => 'France', 'currency_id' => $eur, 'tax_rate' => 20.00, 'phone_code' => '+33', 'sort_order' => 4],
            ['code' => 'IT', 'name' => 'Italy', 'currency_id' => $eur, 'tax_rate' => 22.00, 'phone_code' => '+39', 'sort_order' => 5],
            ['code' => 'ES', 'name' => 'Spain', 'currency_id' => $eur, 'tax_rate' => 21.00, 'phone_code' => '+34', 'sort_order' => 6],
            ['code' => 'AT', 'name' => 'Austria', 'currency_id' => $eur, 'tax_rate' => 20.00, 'phone_code' => '+43', 'sort_order' => 7],
            ['code' => 'PT', 'name' => 'Portugal', 'currency_id' => $eur, 'tax_rate' => 23.00, 'phone_code' => '+351', 'sort_order' => 8],
            ['code' => 'LU', 'name' => 'Luxembourg', 'currency_id' => $eur, 'tax_rate' => 17.00, 'phone_code' => '+352', 'sort_order' => 9],
            ['code' => 'IE', 'name' => 'Ireland', 'currency_id' => $eur, 'tax_rate' => 23.00, 'phone_code' => '+353', 'sort_order' => 10],
            ['code' => 'GB', 'name' => 'United Kingdom', 'currency_id' => $gbp, 'tax_rate' => 20.00, 'phone_code' => '+44', 'sort_order' => 11],
            ['code' => 'CH', 'name' => 'Switzerland', 'currency_id' => $chf, 'tax_rate' => 7.70, 'phone_code' => '+41', 'sort_order' => 12],
            ['code' => 'SE', 'name' => 'Sweden', 'currency_id' => $sek, 'tax_rate' => 25.00, 'phone_code' => '+46', 'sort_order' => 13],
            ['code' => 'NO', 'name' => 'Norway', 'currency_id' => $nok, 'tax_rate' => 25.00, 'phone_code' => '+47', 'sort_order' => 14],
            ['code' => 'DK', 'name' => 'Denmark', 'currency_id' => $dkk, 'tax_rate' => 25.00, 'phone_code' => '+45', 'sort_order' => 15],
            ['code' => 'FI', 'name' => 'Finland', 'currency_id' => $eur, 'tax_rate' => 24.00, 'phone_code' => '+358', 'sort_order' => 16],
            ['code' => 'PL', 'name' => 'Poland', 'currency_id' => $eur, 'tax_rate' => 23.00, 'phone_code' => '+48', 'sort_order' => 17],
            ['code' => 'CZ', 'name' => 'Czech Republic', 'currency_id' => $eur, 'tax_rate' => 21.00, 'phone_code' => '+420', 'sort_order' => 18],
            ['code' => 'GR', 'name' => 'Greece', 'currency_id' => $eur, 'tax_rate' => 24.00, 'phone_code' => '+30', 'sort_order' => 19],
            ['code' => 'HU', 'name' => 'Hungary', 'currency_id' => $eur, 'tax_rate' => 27.00, 'phone_code' => '+36', 'sort_order' => 20],
        ];

        foreach ($countries as $country) {
            \App\Models\Country::create($country);
        }
    }
}
