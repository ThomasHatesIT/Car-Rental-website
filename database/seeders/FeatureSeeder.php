<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature;
class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $features = [
            'GPS',
            'Electric',
            'Bluetooth',
            'Solar Powered',
            'Heated Seats',
            'Leather Interior',
            'All-Wheel Drive',
            'Cruise Control',
            'Sunroof',
            'Rear Camera',
            'Keyless Entry',
            'Blind Spot Monitor',
            'Autopilot',
            'Lane Assist',
            'Parking Sensors',
            'Touchscreen Display',
            'Voice Control',
            'Premium Audio',
            'Dual-Zone Climate Control'
        ];

        foreach ($features as $feature) {
            Feature::create(['name' => $feature]);
        }
    }
}
