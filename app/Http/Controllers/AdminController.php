<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Feature; // Assuming you have a Feature model
use App\Models\CarImage; // Assuming this is your CarImage model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //  For database transactions
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // For more complex validation rules if needed
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Validator;

class AdminController extends Controller



{

     public function __construct(){
       
        $this->middleware('permission:view admin dashboard')->only(['adminHome', 'index', 'carCreate']);

  
    }
     public function adminHome(){

            

                return view('layouts.admin');

        }

     public function index()
{
    $cars = Car::with('featuredImage', 'images')
               ->latest() // Orders by 'created_at' DESC
               ->get();

    return view('admin.cars.index', [
        'cars' => $cars
    ]);
}


         public function carShow(string $id)
    {
        $car = Car::with(['features', 'featuredImage', 'images'])->findOrFail($id);
        return view('admin.cars.show', compact('car')); // Pass 'car' not an array with 'car' key
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
        Log::info('carStore method initiated.');
        Log::info('Incoming request data (excluding files initially):', $request->except(['_token', 'images'])); // Log form data
        Log::info('Request has "images" field (form input name check):', ['has_images_field' => $request->has('images')]);
        Log::info('Request has uploaded files for "images":', ['has_uploaded_files' => $request->hasFile('images')]);

        if ($request->hasFile('images')) {
            $uploadedFiles = $request->file('images');
            $fileDetails = array_map(function ($file) {
                return [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                    'is_valid' => $file->isValid(),
                ];
            }, is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles]); // Handle single or multiple files
            Log::info('Uploaded files details:', $fileDetails);
        } else {
            Log::info('No files were uploaded under the "images" field.');
        }

        $validated = $request->validate([
            // Ensure images is treated as an array even if only one is uploaded.
            // 'nullable' means the 'images' key can be absent or null. If present and not null, it must be an array.
            'images' => 'nullable|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB per image; 'required' if 'images' array has an item
            'make' => 'required|string|max:255',
            'custom_make' => 'nullable|required_if:make,other|string|max:255', // Required if 'make' is 'other'
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1), // Adjusted min year
            'color' => 'nullable|string|max:255',
            'custom_color' => 'nullable|required_if:color,custom|string|max:255', // Required if 'color' is 'custom'
            'license_plate' => ['required', 'string', 'max:10', Rule::unique('cars', 'license_plate')],
            'vin' => ['required', 'string', 'size:17', Rule::unique('cars', 'vin')],
            'transmission' => 'required|in:manual,automatic',
            'fuel_type' => 'required|in:petrol,diesel,hybrid,electric',
            'seats' => 'required|string', // Consider if this should be integer after normalization
            'doors' => 'required|string', // Consider if this should be integer after normalization
            'mileage' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'integer|exists:features,id', // Ensure each feature ID exists in the 'features' table
            'price_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance,out_of_service',
            // 'is_featured' on the Car model itself (different from featured image)
            'is_featured' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        Log::info('Validation passed. Validated data:', $validated);

        // Use a database transaction to ensure all or nothing saves
        DB::beginTransaction();

        try {
            // Determine actual make and color
            $make = ($validated['make'] ?? null) === 'other' ? ($validated['custom_make'] ?? $validated['make']) : ($validated['make'] ?? null);
            $color = ($validated['color'] ?? null) === 'custom' ? ($validated['custom_color'] ?? $validated['color']) : ($validated['color'] ?? null);

            // Normalize seats and doors
            $seats = ($validated['seats'] ?? '0') === '9+' ? 9 : (int)($validated['seats'] ?? 0);
            $doors = (int)($validated['doors'] ?? 0);

            $carData = [
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
                'is_featured' => $request->boolean('is_featured'), // Handles 'on', '1', true, etc.
                'description' => $validated['description'] ?? null, // Ensure null if not present
            ];

            Log::info('Prepared car data for creation:', $carData);

            // 1. Create the Car
            $car = Car::create($carData);
            Log::info("Car created successfully. Car ID: {$car->id}", $car->toArray());

            // 2. Attach Features (if any)
            if (!empty($validated['features'])) {
                Log::info("Attaching features to Car ID: {$car->id}", ['features_ids' => $validated['features']]);
                $car->features()->attach($validated['features']);
                Log::info('Features attached successfully.');
            } else {
                Log::info("No features to attach for Car ID: {$car->id}.");
            }

            // 3. Handle and Store Images (if any)
            if ($request->hasFile('images') && !empty($validated['images'])) { // Check validated['images'] too
                Log::info("Starting image handling process for Car ID: {$car->id}.");
                // Ensure $validated['images'] (which are the actual UploadedFile objects after validation) are iterated
                foreach ($validated['images'] as $index => $image) {
                    // Note: $request->file('images') returns the original array of UploadedFile objects.
                    // $validated['images'] contains the UploadedFile objects that passed validation.
                    // It's generally safer to iterate over $validated['images'].
                    Log::info("Processing image index: {$index} for Car ID: {$car->id}");
                    if ($image->isValid()) {
                        Log::info("Image [{$index}] is valid. Original name: " . $image->getClientOriginalName() . ", Size: " . $image->getSize());
                        try {
                            // Store in 'cars/{car_id}/images' for better organization if desired, or 'Car' as before.
                            // Using 'Car' subdirectory as per original logic.
                            $path = $image->store('Car', 'public');
                            Log::info("Image [{$index}] stored. Path: {$path}");

                            if (Storage::disk('public')->exists($path)) {
                                Log::info("Verified: File [{$path}] exists on public disk.");
                            } else {
                                Log::error("VERIFICATION FAILED: File [{$path}] DOES NOT exist on public disk after store command for Car ID: {$car->id}.");
                                // Consider throwing an exception here to trigger transaction rollback if file storage is critical
                                // throw new \Exception("Failed to verify storage of image: {$path}");
                            }

                            // Create CarImage record and associate with the car
                            // The first image uploaded (index 0) is marked as featured for the image collection
                            $carImage = $car->images()->create([
                                'path' => $path,
                                'is_featured' => $index === 0
                            ]);
                            Log::info("CarImage record created for Car ID: {$car->id}, Image Path: {$path}. DB Record:", $carImage->toArray());

                        } catch (\Exception $e) {
                            Log::error("Error storing/saving image [{$index}] for Car ID: {$car->id}: " . $e->getMessage(), ['exception' => $e]);
                            // Re-throw to trigger transaction rollback
                            throw $e;
                        }
                    } else {
                        Log::error("Image [{$index}] for Car ID: {$car->id} is not valid. Error code: " . $image->getError() . " - " . $image->getErrorMessage());
                        // Consider if an invalid file (post-validation, which is rare) should halt the process
                        // throw new \Exception("An uploaded image was found to be invalid post-validation: " . $image->getClientOriginalName());
                    }
                }
                Log::info("Finished image handling process for Car ID: {$car->id}.");
            } else {
                Log::info("No valid images found in request to handle for Car ID: {$car->id}.");
            }

            // If all operations were successful, commit the transaction
            DB::commit();
            Log::info("Transaction committed. Car ID: {$car->id} and associated data saved successfully.");

            return redirect('/admin/cars')->with('success', 'Car created successfully!'); // Assuming /admin/cars is your index route

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation exceptions are typically handled by Laravel automatically,
            // but if caught here, rollback and re-throw or handle.
            DB::rollBack();
            Log::error('Validation failed during carStore: ' . $e->getMessage(), ['errors' => $e->errors()]);
            throw $e; // Re-throw to let Laravel handle the redirect back with errors
        } catch (\Exception $e) {
            // For any other exceptions, rollback the transaction
            DB::rollBack();
            Log::error("Error during car creation or related operations (transaction rolled back): " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString() // More detailed for debugging
            ]);

            return redirect()->back()
                             ->withInput() // Send back old input to repopulate the form
                             ->with('error', 'Failed to create car. An unexpected error occurred. Please try again. Details: ' . $e->getMessage());
        }
    }
}