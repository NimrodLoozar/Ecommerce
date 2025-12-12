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
        // Create owner/admin user - owns the platform and all default cars
        $owner = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Platform Owner',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        // Check if owner dealer profile already exists
        if (!$owner->dealerProfile) {
            // Create owner's dealer profile
            DealerProfile::create([
                'user_id' => $owner->id,
                'company_name' => 'CarHub Platform',
                'business_registration' => 'BR-2025-OWNER',
                'tax_id' => 'TAX-OWNER-001',
                'description' => 'Official platform dealer account. We manage the CarHub platform and offer a curated selection of quality vehicles from trusted sources.',
                'phone' => '+1 (555) 000-0001',
                'website' => 'https://carhub.example.com',
                'commission_rate' => 0.00, // Platform owner doesn't pay commission
                'subscription_plan' => 'enterprise',
                'status' => 'approved',
                'approved_by' => null,
                'approved_at' => now(),
            ]);

            $this->command->info('Owner dealer profile created successfully!');
        } else {
            $this->command->warn('Owner dealer profile already exists!');
        }

        $this->command->info('Email: owner@example.com');
        $this->command->info('Password: password');
        $this->command->info('Company: CarHub Platform');
        $this->command->info('---');

        // Find or create regular dealer user
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

