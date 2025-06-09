<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use App\Models\Feature;
use App\Models\CarImage;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str; // For Str::slug or similar for image names

class AdminController extends Controller
{
    public function __construct(){
        // Consider applying permissions to more methods like edit, update, destroy, carShow, carStore
        $this->middleware('permission:view admin dashboard')->only(['adminHome']); // Only for dashboard
        $this->middleware('permission:view cars')->only(['index', 'carShow']);
        $this->middleware('permission:create cars')->only(['carCreate', 'carStore']);
        $this->middleware('permission:edit cars')->only(['edit', 'update', 'setFeaturedImage']);
        $this->middleware('permission:delete cars')->only(['destroy']);
        // Add $this->middleware('permission:delete cars')->only(['destroy']); when you implement it
    }

   public function adminHome()
{
    // KPIs
    $totalCars = Car::count();
    $availableCars = Car::where('status', 'available')->count();
    // Rented cars: cars associated with an active booking
    $rentedCarsCount = Car::whereHas('bookings', function ($query) {
        $query->where('status', 'active'); // Assuming BookingStatus Enum or string
    })->count();

    $totalUsers = User::whereHas('roles', function ($query) {
        $query->where('name', 'user');
    })->count(); // Count only 'user' role

    $pendingBookingsCount = Booking::where('status', 'pending')->count(); // Or BookingStatus::PENDING
    $activeBookingsCount = Booking::where('status', 'active')->count();   // Or BookingStatus::ACTIVE

    // Recent Activity
    $latestPendingBookings = Booking::with(['user', 'car'])
        ->where('status', 'pending') // Or BookingStatus::PENDING
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $recentlyAddedCars = Car::orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $carsDueForReturn = Booking::with(['user', 'car'])
        ->where('status', 'active') // Or BookingStatus::ACTIVE
        ->whereDate('end_date', '<=', Carbon::tomorrow()) // Today or tomorrow
        ->whereDate('end_date', '>=', Carbon::today())   // Not already past
        ->orderBy('end_date', 'asc')
        ->take(5)
        ->get();

    return view('admin.dashboard', compact(
        'totalCars',
        'availableCars',
        'rentedCarsCount',
        'totalUsers',
        'pendingBookingsCount',
        'activeBookingsCount',
        'latestPendingBookings',
        'recentlyAddedCars',
        'carsDueForReturn'
        // Add more data as you implement charts etc.
    ));
}

    public function index()
    {
        $cars = Car::with(['featuredImage', 'images'])->latest()->paginate(10);
        $totalCars = Car::count();
        $availableCars = Car::where('status', 'available')->count();
        $unavailableCars = $totalCars - $availableCars;

        return view('admin.cars.index', compact('cars', 'totalCars', 'availableCars', 'unavailableCars'));
    }

    public function carShow(Car $car) // Use route model binding
    {
        $car->load(['features', 'featuredImage', 'images']);
        return view('admin.cars.show', compact('car'));
    }

    public function carCreate(){
        $features = Feature::orderBy('name')->get(); // Get all features
        // Pass an empty car or null, not all cars
        return view('admin.cars.create', compact('features'));
    }

    public function carStore(Request $request)
    {
        Log::info('carStore method initiated.');
        Log::info('Incoming request data (excluding files initially):', $request->except(['_token', 'images']));
        Log::info('Request has "images" field (form input name check):', ['has_images_field' => $request->has('images')]);
        Log::info('Request has uploaded files for "images":', ['has_uploaded_files' => $request->hasFile('images')]);

        if ($request->hasFile('images')) {
            $uploadedFiles = $request->file('images');
            $fileDetails = array_map(function ($file) {
                return ['original_name' => $file->getClientOriginalName(), 'size' => $file->getSize(), 'mime_type' => $file->getClientMimeType(), 'is_valid' => $file->isValid()];
            }, is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles]);
            Log::info('Uploaded files details:', $fileDetails);
        } else {
            Log::info('No files were uploaded under the "images" field.');
        }

        $validated = $request->validate([
            'images' => 'nullable|array|max:5', // Max 5 NEW images can be uploaded at once
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'name' => ['nullable', 'string', 'max:255'],
            'make' => 'required_without:custom_make|string|max:255',
            'custom_make' => 'nullable|required_if:make,other|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|required_without:custom_color|string|max:255',
            'custom_color' => 'nullable|required_if:color,custom|string|max:255',
            'license_plate' => ['required', 'string', 'max:10', Rule::unique('cars', 'license_plate')],
            'vin' => ['required', 'string', 'alpha_num', 'size:17', Rule::unique('cars', 'vin')],
            'transmission' => 'required|in:manual,automatic',
            'fuel_type' => 'required|in:petrol,diesel,hybrid,electric',
            'seats' => 'required|string|max:5',
            'doors' => 'required|integer|min:2|max:5',
            'mileage' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'integer|exists:features,id',
            'price_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance,out_of_service',
            'is_featured' => 'nullable|boolean',
            'description' => 'nullable|string|max:5000',
        ]);

        Log::info('Validation passed. Validated data:', $validated);

        DB::beginTransaction();
        try {
            $make = ($validated['make'] ?? null) === 'other' ? ($validated['custom_make'] ?? $validated['make']) : ($validated['make'] ?? null);
            $color = ($validated['color'] ?? null) === 'custom' ? ($validated['custom_color'] ?? $validated['color']) : ($validated['color'] ?? null);
            $seats = ($validated['seats'] ?? '0') === '9+' ? 9 : (int)($validated['seats'] ?? 0); // Assuming 'seats' on DB is integer
            $doors = (int)($validated['doors'] ?? 0); // Assuming 'doors' on DB is integer

            $carData = [
                'name' => $validated['name'] ?? ($make . ' ' . $validated['model']), // Auto-generate name if not provided
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
                'is_featured' => $request->boolean('is_featured'),
                'description' => $validated['description'] ?? null,
            ];

            Log::info('Prepared car data for creation:', $carData);
            $car = Car::create($carData);
            Log::info("Car created successfully. Car ID: {$car->id}", $car->toArray());

            if (!empty($validated['features'])) {
                Log::info("Attaching features to Car ID: {$car->id}", ['features_ids' => $validated['features']]);
                $car->features()->attach($validated['features']);
                Log::info('Features attached successfully.');
            }

            if ($request->hasFile('images') && !empty($validated['images'])) {
                Log::info("Starting image handling process for Car ID: {$car->id}.");
                $firstUploadedImageId = null;
                foreach ($validated['images'] as $index => $imageFile) {
                    if ($imageFile->isValid()) {
                        $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $sanitizedName = Str::slug($originalName);
                        $extension = $imageFile->getClientOriginalExtension();
                        $fileName = 'car_' . $car->id . '_' . $sanitizedName . '_' . time() . '_' . Str::random(3) . '.' . $extension;
                        $path = $imageFile->storeAs('cars/' . $car->id, $fileName, 'public');
                        Log::info("Image [{$index}] stored. Path: {$path}");

                        $carImage = $car->images()->create(['path' => $path]);
                        Log::info("CarImage record created for Car ID: {$car->id}, Image Path: {$path}. DB Record:", $carImage->toArray());
                        if ($index === 0) { // Track the first uploaded image
                            $firstUploadedImageId = $carImage->id;
                        }
                    }
                }
                // Set the first uploaded image as featured
                if ($firstUploadedImageId) {
                    $car->update(['featured_image_id' => $firstUploadedImageId]);
                    Log::info("Set featured image for Car ID: {$car->id} to Image ID: {$firstUploadedImageId}");
                }
                Log::info("Finished image handling process for Car ID: {$car->id}.");
            } else {
                Log::info("No valid images found in request to handle for Car ID: {$car->id}.");
            }

            DB::commit();
            Log::info("Transaction committed. Car ID: {$car->id} and associated data saved successfully.");
            return redirect()->route('admin.cars.index')->with('success', 'Car created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation failed during carStore: ' . $e->getMessage(), ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error during car creation or related operations (transaction rolled back): " . $e->getMessage(), ['exception' => $e, 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Failed to create car. An unexpected error occurred. Please try again. Details: ' . $e->getMessage());
        }
    }

    public function edit(Car $car)
    {
        $features = Feature::orderBy('name')->get();
        $car->load('images', 'features');
        return view('admin.cars.edit', compact('car', 'features'));
    }

    public function update(Request $request, Car $car)
    {
        Log::info("Update method initiated for Car ID: {$car->id}.");
        Log::info('Incoming update request data (excluding files initially):', $request->except(['_token', '_method', 'images']));
        Log::info('Request has "images" field for update (form input name check):', ['has_images_field' => $request->has('images')]);
        Log::info('Request has uploaded files for "images" for update:', ['has_uploaded_files' => $request->hasFile('images')]);

        $validatedData = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'make' => ['required_without:custom_make', 'string', 'max:100'],
            'custom_make' => ['nullable', 'required_if:make,other', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color' => ['nullable', 'required_without:custom_color', 'string', 'max:50'],
            'custom_color' => ['nullable', 'required_if:color,custom', 'string', 'max:50'],
            'license_plate' => ['required', 'string', 'max:10', Rule::unique('cars')->ignore($car->id)],
            'vin' => ['required', 'string', 'alpha_num', 'size:17', Rule::unique('cars')->ignore($car->id)],
            'transmission' => ['required', Rule::in(['manual', 'automatic'])],
            'fuel_type' => ['required', Rule::in(['petrol', 'diesel', 'hybrid', 'electric'])],
            'seats' => ['required', 'string', 'max:5'],
            'doors' => ['required', 'integer', 'min:2', 'max:5'],
            'mileage' => ['required', 'integer', 'min:0'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['available', 'rented', 'maintenance', 'out_of_service'])],
            'is_featured' => ['nullable', 'boolean'], // Car level featured flag
            'description' => ['nullable', 'string', 'max:5000'],
            'features' => ['nullable', 'array'],
            'features.*' => ['exists:features,id'],
            'images' => ['nullable', 'array'], // Validate the array itself
             // Max 5 *new* images can be uploaded, this rule applies to the 'images' array items
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:car_images,id'],
            'make_featured_image_id' => ['nullable', 'integer', 'exists:car_images,id'], // If using this for explicit featured set
        ]);
        Log::info('Validation passed for update. Validated data:', $validatedData);

        DB::beginTransaction();
        try {
            // --- START: Handle "Set Featured Image" from a specific button click ---
            // This gives priority to an explicit "Set Featured" action
            if ($request->has('make_featured_image_id')) {
                $imageIdToMakeFeatured = $request->input('make_featured_image_id');
                // Ensure the image belongs to this car
                $imageToFeature = CarImage::where('id', $imageIdToMakeFeatured)->where('car_id', $car->id)->first();
                if ($imageToFeature) {
                    $car->update(['featured_image_id' => $imageToFeature->id]);
                    Log::info("Explicitly set featured image for Car ID: {$car->id} to Image ID: {$imageToFeature->id}");
                } else {
                    Log::warning("Attempted to set non-existent or mismatched image ID {$imageIdToMakeFeatured} as featured for Car ID: {$car->id}.");
                }
            }
            // --- END: Handle "Set Featured Image" ---


            $carData = $validatedData;
            $carData['make'] = ($validatedData['make'] ?? $car->make) === 'other' ? ($validatedData['custom_make'] ?? $car->make) : ($validatedData['make'] ?? $car->make);
            $carData['color'] = ($validatedData['color'] ?? $car->color) === 'custom' ? ($validatedData['custom_color'] ?? $car->color) : ($validatedData['color'] ?? $car->color);
            unset($carData['custom_make'], $carData['custom_color']);

            $carData['seats'] = ($validatedData['seats'] ?? $car->seats) === '9+' ? 9 : (int)($validatedData['seats'] ?? $car->seats);
            $carData['doors'] = (int)($validatedData['doors'] ?? $car->doors);

            $carData['is_featured'] = $request->boolean('is_featured'); // Car level featured

            Log::info('Prepared car data for update:', $carData);
            $car->update($carData);
            Log::info("Car basic info updated for Car ID: {$car->id}.");

            if ($request->has('features')) {
                $car->features()->sync($validatedData['features']);
                Log::info("Synced features for Car ID: {$car->id}.");
            } else {
                $car->features()->detach();
                Log::info("Detached all features for Car ID: {$car->id} as none were provided.");
            }

            if ($request->has('delete_images')) {
                Log::info("Processing images to delete for Car ID: {$car->id}.", ['images_to_delete' => $validatedData['delete_images']]);
                foreach ($validatedData['delete_images'] as $imageIdToDelete) {
                    $image = CarImage::find($imageIdToDelete);
                    if ($image && $image->car_id === $car->id) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                        Log::info("Deleted image ID: {$imageIdToDelete}, Path: {$image->path} for Car ID: {$car->id}.");
                        if ($car->featured_image_id === (int)$imageIdToDelete) { // Cast to int for safety
                            $car->update(['featured_image_id' => null]);
                            Log::info("Unset featured image ID for Car ID: {$car->id} because it was deleted.");
                        }
                    }
                }
            }
            $car->refresh(); // Refresh to get updated count after deletes

            // Handle new image uploads
            $newlyUploadedImageId = null;
            if ($request->hasFile('images') && !empty($validatedData['images'])) {
                $currentImageCount = $car->images()->count();
                $canUploadCount = 5 - $currentImageCount; // Max 5 total images

                Log::info("Processing new image uploads for Car ID: {$car->id}. Current images: {$currentImageCount}, Can upload: {$canUploadCount}");

                if ($canUploadCount > 0) {
                    $uploadedImageFiles = $validatedData['images']; // These are the validated UploadedFile objects
                    foreach ($uploadedImageFiles as $index => $file) {
                        if ($index >= $canUploadCount) {
                            Log::info("Reached max image upload limit for Car ID: {$car->id}. Skipping remaining new images.");
                            break;
                        }
                        if ($file->isValid()) {
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $sanitizedName = Str::slug($originalName);
                            $extension = $file->getClientOriginalExtension();
                            $fileName = 'car_' . $car->id . '_' . $sanitizedName . '_' . time() . '_' . Str::random(3) . '.' . $extension;
                            $path = $file->storeAs('cars/' . $car->id, $fileName, 'public');
                            Log::info("Stored new image [{$index}] for Car ID: {$car->id}. Path: {$path}");

                            $newImage = $car->images()->create(['path' => $path]);
                            Log::info("Created CarImage record for new image. ID: {$newImage->id}");
                            if ($index === 0 && empty($car->featured_image_id) && !$request->has('make_featured_image_id')) {
                                // Only set as featured if no explicit featured image was set by button
                                // and no featured image exists yet.
                                $newlyUploadedImageId = $newImage->id;
                            }
                        } else {
                             Log::error("A new image file was invalid post-validation. Original name: {$file->getClientOriginalName()}");
                        }
                    }
                } else {
                    Log::info("No slots available to upload new images for Car ID: {$car->id}. Max limit reached.");
                }
            }

            $car->refresh(); // Refresh again after new uploads

            // Smartly set featured image
            // 1. If a "Set Featured" button was clicked, it's already handled and $car->featured_image_id is set.
            // 2. If not, and a new image was uploaded and no featured image was set, use the first new one.
            if (!$request->has('make_featured_image_id') && empty($car->featured_image_id) && $newlyUploadedImageId) {
                $car->update(['featured_image_id' => $newlyUploadedImageId]);
                Log::info("Set first newly uploaded image (ID: {$newlyUploadedImageId}) as featured for Car ID: {$car->id}.");
            }
            // 3. If still no featured image after all operations (e.g., old one deleted, no new ones, no explicit set)
            //    and the car *still has images*, pick the first available one.
            else if (empty($car->featured_image_id) && $car->images()->exists()) {
                $firstExistingImage = $car->images()->orderBy('id')->first();
                if ($firstExistingImage) {
                    $car->update(['featured_image_id' => $firstExistingImage->id]);
                    Log::info("Set first existing image (ID: {$firstExistingImage->id}) as featured for Car ID: {$car->id} as a fallback.");
                }
            }

            DB::commit();
            Log::info("Transaction committed for update. Car ID: {$car->id} updated successfully.");

            // If "Set Featured" button was clicked, and it was the *only* action desired,
            // redirect back to edit page. Otherwise, redirect to show page.
            if ($request->has('make_featured_image_id') && count($request->all()) <= 4) { // _token, _method, _previous, make_featured_image_id
                return redirect()->route('admin.cars.edit', $car->id)->with('success', 'Featured image updated successfully!');
            }

            return redirect()->route('admin.cars.show', $car->id)->with('success', 'Car details updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation failed during car update for Car ID: {$car->id}: ' . $e->getMessage(), ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error during car update or related operations for Car ID: {$car->id} (transaction rolled back): " . $e->getMessage(), ['exception' => $e, 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Failed to update car. An unexpected error occurred. Please try again. Details: ' . $e->getMessage());
        }
    }

    public function setFeaturedImage(Request $request, Car $car, CarImage $image)
    {
        if ($image->car_id !== $car->id) {
            return back()->with('error', 'Invalid image selection. The image does not belong to this car.');
        }
        $car->update(['featured_image_id' => $image->id]);
        return back()->with('success', 'Featured image updated successfully.');
    }

    // You'll also need a destroy method if you have delete buttons
     public function destroy(Car $car)
     {
        DB::beginTransaction();
         try {
           
            foreach ($car->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
            // Detach features
            $car->features()->detach();
            // Delete the car
            $car->delete();
            DB::commit();
            return redirect()->route('admin.cars.index')->with('success', 'Car deleted successfully.');
         } catch (\Exception $e) {
            DB::rollBack();
       Log::error("Error deleting car ID: {$car->id} - " . $e->getMessage());
             return redirect()->route('admin.cars.index')->with('error', 'Failed to delete car.');
        }
    }


   
      public function indexUsers(Request $request)
{
    // Get all available roles for the filter dropdown
    $roles = \Spatie\Permission\Models\Role::all();
    
    // Start building the query
    $query = User::with(['roles', 'bookings'])
                 ->withCount('bookings');
    
    // Apply search filter
    if ($request->filled('search')) {
        $searchTerm = $request->get('search');
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('email', 'LIKE', "%{$searchTerm}%");
        });
    }
    
    // Apply role filter
    if ($request->filled('role')) {
        $query->whereHas('roles', function($q) use ($request) {
            $q->where('name', $request->get('role'));
        });
    }
    
    // Apply status filter
    if ($request->filled('status')) {
        $status = $request->get('status');
        if ($status === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($status === 'unverified') {
            $query->whereNull('email_verified_at');
        }
    }
    
    // Apply sorting
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    
    // Validate sort parameters
    $allowedSortFields = ['name', 'email', 'created_at', 'bookings_count'];
    $allowedSortOrders = ['asc', 'desc'];
    
    if (in_array($sortBy, $allowedSortFields) && in_array($sortOrder, $allowedSortOrders)) {
        if ($sortBy === 'bookings_count') {
            $query->orderBy('bookings_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
    } else {
        // Default sorting
        $query->orderBy('created_at', 'desc');
    }
    
    // Paginate results
    $perPage = $request->get('per_page', 15);
    $perPage = in_array($perPage, [10, 15, 25, 50]) ? $perPage : 15;
    
    $users = $query->paginate($perPage);
    
    // If this is an AJAX request (for live search), return JSON
    if ($request->ajax()) {
        return response()->json([
            'html' => view('admin.users.partials.table', compact('users'))->render(),
            'pagination' => $users->withQueryString()->links()->render()
        ]);
    }
    
    return view('admin.users.index', [
        'users' => $users,
        'roles' => $roles,
    ]);
}
    }
