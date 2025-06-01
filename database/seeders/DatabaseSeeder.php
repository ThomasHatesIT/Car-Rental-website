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
    $this->call([
          
            BookingSeeder::class,    // Add your new seeder here
        ]);
}
}