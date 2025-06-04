<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
       'make' => $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'BMW', 'Audi']),
            'model' => $this->faker->word(),
            'year' => $this->faker->numberBetween(2015, 2024),
            'color' => $this->faker->safeColorName(),
            'license_plate' => strtoupper($this->faker->bothify('???-####')),
            'vin' => strtoupper($this->faker->bothify('??######??######')),

            // Enums
            'transmission' => $this->faker->randomElement(['manual', 'automatic']),
            'fuel_type' => $this->faker->randomElement(['petrol', 'diesel', 'electric', 'hybrid']),

            'seats' => $this->faker->numberBetween(2, 7),
            'doors' => $this->faker->numberBetween(2, 5),
            'price_per_day' => $this->faker->randomFloat(2, 30, 200),
            'mileage' => $this->faker->numberBetween(10000, 100000),
            'description' => $this->faker->paragraph(),

            
       

            'status' => $this->faker->randomElement(['available', 'rented', 'maintenance', 'out_of_service']),
            'is_featured' => $this->faker->boolean(20),
        ];
    }
}
