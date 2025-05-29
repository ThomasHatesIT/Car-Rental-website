<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feature>
 */
class FeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
     'name' => $this->faker->randomElement([
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
]),

        ];
    }
}
