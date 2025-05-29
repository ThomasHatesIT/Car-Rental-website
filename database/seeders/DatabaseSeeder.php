<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;   
   use App\Models\Car;
use App\Models\Feature;

class DatabaseSeeder extends Seeder
{
   

public function run()
{
    // Seed features first
    Feature::insert([
        ['name' => 'GPS'],
        ['name' => 'Bluetooth'],
        ['name' => 'Air Conditioning'],
        ['name' => 'Heated Seats'],
        ['name' => 'Backup Camera'],
    ]);

    // Create 10 cars
    Car::factory(10)->create()->each(function ($car) {
        // Attach 2 to 4 random features to each car
        $featureIds = Feature::inRandomOrder()->take(rand(2, 4))->pluck('id');
        $car->features()->attach($featureIds);
    });
}

}
