<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Feature;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


use Illuminate\Support\Facades\Validator;

class AdminController extends Controller



{
     public function adminHome(){

            

                return view('layouts.admin');

        }

        public function index(){

                $cars = Car::all();

                return view('admin.cars.index', [
                    'cars' => $cars
                ]);

        }

        public function carCreate(){

 
                $cars = Car::all();
                $features = Feature::all();
                return view('admin.cars.create', [
                    'car' => $cars,
                    'features' =>  $features
                ]);

        }

                public function carStore(Request $request)
            {
                $validated = $request->validate([
                    'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB per image
                    'make' => 'required|string|max:255',
                    'custom_make' => 'nullable|string|max:255',
                    'model' => 'required|string|max:255',
                    'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
                    'color' => 'nullable|string|max:255',
                    'custom_color' => 'nullable|string|max:255',
                    'license_plate' => 'required|string|max:10|unique:cars,license_plate',
                    'vin' => 'required|string|size:17|unique:cars,vin',
                    'transmission' => 'required|in:manual,automatic',
                    'fuel_type' => 'required|in:petrol,diesel,hybrid,electric',
                    'seats' => 'required|string',
                    'doors' => 'required|string',
                    'mileage' => 'required|integer|min:0',
                    'features' => 'nullable|array',
                    'features.*' => 'integer|exists:features,id',
                    'price_per_day' => 'required|numeric|min:0',
                    'status' => 'required|in:available,rented,maintenance,out_of_service',
                    'is_featured' => 'nullable|boolean',
                    'description' => 'nullable|string',
                ]);

                // Determine actual make and color
                $make = $validated['make'] === 'other' ? $validated['custom_make'] : $validated['make'];
                $color = $validated['color'] === 'custom' ? $validated['custom_color'] : $validated['color'];

                // Normalize seats and doors
                $seats = $validated['seats'] === '9+' ? 9 : (int)$validated['seats'];
                $doors = (int)$validated['doors'];

                $car = Car::create([
                    'make' => $make,
                    'model' => $validated['model'],
                    'year' => $validated['year'],
                    'color' => $color,
                    'license_plate' => strtoupper($validated['license_plate']),
                    'vin' => strtoupper($validated['vin']),
                    'transmission' => $validated['transmission'],
                    'fuel_type' => $validated['fuel_type'],
                    'seats' => $seats,
                    'doors' => $doors,
                    'mileage' => $validated['mileage'],
                    'price_per_day' => $validated['price_per_day'],
                    'status' => $validated['status'],
                    'is_featured' => $request->has('is_featured'),
                    'description' => $validated['description'],
                ]);

                // Attach features
                if (!empty($validated['features'])) {
                   $car->features()->attach($validated['features']);
                }

                // Handle images
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $index => $image) {
                        $path = $image->store('', 'public');
                       $car->images()->create([
                            'path' => $path,
                            'is_featured' => $index === 0 // first image as featured
                        ]);
                    }
                }

                return redirect('admin')->with('success', 'Car created successfully!');
            }
}
