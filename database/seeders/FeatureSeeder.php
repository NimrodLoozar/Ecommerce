<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            // Safety Features
            ['name' => 'ABS Brakes', 'category' => 'safety', 'icon' => 'shield-check'],
            ['name' => 'Airbags', 'category' => 'safety', 'icon' => 'shield'],
            ['name' => 'Backup Camera', 'category' => 'safety', 'icon' => 'camera'],
            ['name' => 'Blind Spot Monitoring', 'category' => 'safety', 'icon' => 'eye'],
            ['name' => 'Lane Departure Warning', 'category' => 'safety', 'icon' => 'alert-triangle'],
            ['name' => 'Parking Sensors', 'category' => 'safety', 'icon' => 'radio'],
            ['name' => 'Traction Control', 'category' => 'safety', 'icon' => 'disc'],
            ['name' => 'Stability Control', 'category' => 'safety', 'icon' => 'anchor'],
            ['name' => 'Collision Warning', 'category' => 'safety', 'icon' => 'alert-circle'],
            ['name' => 'Emergency Braking', 'category' => 'safety', 'icon' => 'octagon'],

            // Comfort Features
            ['name' => 'Leather Seats', 'category' => 'comfort', 'icon' => 'armchair'],
            ['name' => 'Heated Seats', 'category' => 'comfort', 'icon' => 'thermometer'],
            ['name' => 'Ventilated Seats', 'category' => 'comfort', 'icon' => 'wind'],
            ['name' => 'Power Seats', 'category' => 'comfort', 'icon' => 'settings'],
            ['name' => 'Memory Seats', 'category' => 'comfort', 'icon' => 'save'],
            ['name' => 'Sunroof', 'category' => 'comfort', 'icon' => 'sun'],
            ['name' => 'Panoramic Roof', 'category' => 'comfort', 'icon' => 'maximize'],
            ['name' => 'Climate Control', 'category' => 'comfort', 'icon' => 'snowflake'],
            ['name' => 'Dual Climate Control', 'category' => 'comfort', 'icon' => 'thermometer-sun'],
            ['name' => 'Cruise Control', 'category' => 'comfort', 'icon' => 'target'],
            ['name' => 'Adaptive Cruise Control', 'category' => 'comfort', 'icon' => 'navigation'],

            // Technology Features
            ['name' => 'GPS Navigation', 'category' => 'technology', 'icon' => 'map'],
            ['name' => 'Bluetooth', 'category' => 'technology', 'icon' => 'bluetooth'],
            ['name' => 'Apple CarPlay', 'category' => 'technology', 'icon' => 'smartphone'],
            ['name' => 'Android Auto', 'category' => 'technology', 'icon' => 'smartphone'],
            ['name' => 'Premium Sound System', 'category' => 'technology', 'icon' => 'music'],
            ['name' => 'Touchscreen Display', 'category' => 'technology', 'icon' => 'tablet'],
            ['name' => 'Wireless Charging', 'category' => 'technology', 'icon' => 'battery-charging'],
            ['name' => 'USB Ports', 'category' => 'technology', 'icon' => 'usb'],
            ['name' => 'Keyless Entry', 'category' => 'technology', 'icon' => 'key'],
            ['name' => 'Push Button Start', 'category' => 'technology', 'icon' => 'power'],
            ['name' => 'Remote Start', 'category' => 'technology', 'icon' => 'radio'],
            ['name' => 'WiFi Hotspot', 'category' => 'technology', 'icon' => 'wifi'],

            // Performance Features
            ['name' => 'All-Wheel Drive', 'category' => 'performance', 'icon' => 'settings'],
            ['name' => 'Four-Wheel Drive', 'category' => 'performance', 'icon' => 'truck'],
            ['name' => 'Sport Mode', 'category' => 'performance', 'icon' => 'zap'],
            ['name' => 'Adaptive Suspension', 'category' => 'performance', 'icon' => 'activity'],
            ['name' => 'Turbocharger', 'category' => 'performance', 'icon' => 'wind'],
            ['name' => 'Limited Slip Differential', 'category' => 'performance', 'icon' => 'disc'],

            // Exterior Features
            ['name' => 'LED Headlights', 'category' => 'exterior', 'icon' => 'sun'],
            ['name' => 'Fog Lights', 'category' => 'exterior', 'icon' => 'cloud'],
            ['name' => 'Alloy Wheels', 'category' => 'exterior', 'icon' => 'disc'],
            ['name' => 'Roof Rack', 'category' => 'exterior', 'icon' => 'package'],
            ['name' => 'Tow Hitch', 'category' => 'exterior', 'icon' => 'link'],
            ['name' => 'Power Liftgate', 'category' => 'exterior', 'icon' => 'arrow-up-circle'],
        ];

        foreach ($features as $feature) {
            Feature::create([
                'name' => $feature['name'],
                'slug' => Str::slug($feature['name']),
                'icon' => $feature['icon'],
                'category' => $feature['category'],
            ]);
        }

        $this->command->info('Created ' . count($features) . ' features.');
    }
}
