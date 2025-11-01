<?php

namespace Database\Seeders;

use App\Models\DealerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DealerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create dealer user
        $dealer = User::firstOrCreate(
            ['email' => 'dealer@example.com'],
            [
                'name' => 'John Dealer',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        // Check if dealer profile already exists
        if ($dealer->dealerProfile) {
            $this->command->warn('Dealer profile already exists for this user!');
            $this->command->info('Email: dealer@example.com');
            $this->command->info('Company: ' . $dealer->dealerProfile->company_name);
            return;
        }

        // Create dealer profile
        DealerProfile::create([
            'user_id' => $dealer->id,
            'company_name' => 'Premium Auto Sales',
            'business_registration' => 'BR-2025-001',
            'tax_id' => 'TAX-12345678',
            'description' => 'We are a premium dealership specializing in luxury and sports cars. With over 20 years of experience, we provide top-quality vehicles and exceptional customer service.',
            'phone' => '+1 (555) 123-4567',
            'website' => 'https://premiumautosales.example.com',
            'commission_rate' => 10.00,
            'subscription_plan' => 'premium',
            'status' => 'approved',
            'approved_by' => null, // Can be set to admin user ID if needed
            'approved_at' => now(),
        ]);

        $this->command->info('Dealer profile created successfully!');
        $this->command->info('Email: dealer@example.com');
        $this->command->info('Password: password');
        $this->command->info('Company: Premium Auto Sales');
    }
}

