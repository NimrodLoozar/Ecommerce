<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user if doesn't exist
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Seed Phase 1 data
        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            ConditionSeeder::class,
            FeatureSeeder::class,
        ]);

        $this->command->info('Phase 1 seeding completed successfully!');

        // Seed Phase 5 data (Multi-Currency & Regional)
        $this->call([
            CurrencySeeder::class,
            CountrySeeder::class,
            DeliveryZoneSeeder::class,
            TaxRateSeeder::class,
        ]);

        $this->command->info('Phase 5 seeding completed successfully!');
    }
}
