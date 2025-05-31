{{-- resources/views/admin/cars/edit.blade.php --}}
@extends('layouts.admin')

@section('header', 'Edit Car')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.cars.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0M9 17h6"></path></svg>
                    Cars
                </a>
            </li>
            <li class="inline-flex items-center">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('admin.cars.show', $car->id) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">{{ $car->name ?? $car->make . ' ' . $car->model }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit Car</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Car: {{ $car->name ?? $car->make . ' ' . $car->model }}</h1>
        <p class="text-gray-600 mt-1">Update the details for this car.</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT') {{-- Method spoofing for UPDATE --}}
            
            <!-- Vehicle Images Section -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Vehicle Images</h3>
                    <p class="mt-1 text-sm text-gray-500">Upload new images to replace existing ones, or manage current images. Max 5 images.</p>
                </div>

                {{-- Display Existing Images --}}
                @if($car->images->isNotEmpty())
                <div class="mb-4">
                    <h4 class="text-md font-medium text-gray-700 mb-2">Current Images:</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($car->images as $image)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Car image {{ $loop->iteration }}" class="h-32 w-full object-cover rounded-lg border">
                            <div class="absolute top-1 right-1">
                                <label for="delete_image_{{ $image->id }}" class="flex items-center bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1 rounded-full cursor-pointer shadow">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="delete_image_{{ $image->id }}" class="h-3 w-3 text-red-400 border-gray-300 rounded focus:ring-red-500 mr-1">
                                    Delete
                                </label>
                            </div>
                            @if($image->id === $car->featured_image_id)
                                <span class="absolute bottom-1 left-1 bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">Featured</span>
                            @else
                                <button type="submit" name="set_featured_image" value="{{ $image->id }}"
                                        formaction="{{ route('admin.cars.setFeaturedImage', ['car' => $car->id, 'image' => $image->id]) }}"
                                        formmethod="POST"
                                        class="absolute bottom-1 left-1 bg-gray-500 hover:bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                    Set Featured
                                </button>
                                <!-- Need a separate CSRF for this button if it submits a different form -->
                                {{-- Add @csrf inside this button's form if you make it a separate form post for setting featured image --}}
                            @endif
                        </div>
                        @endforeach
                    </div>
                     <p class="mt-2 text-xs text-gray-500">Check "Delete" to remove an image on save. To change the featured image, click "Set Featured" on an existing image (this will save immediately) or ensure the first newly uploaded image is the one you want as featured.</p>
                </div>
                @endif


                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="image-upload-container">
                    <!-- Upload Box -->
                    <div class="relative group">
                        <div id="upload-box" class="h-40 border-2 border-dashed border-gray-300 rounded-lg flex flex-col justify-center items-center hover:border-blue-500 transition duration-200 bg-gray-50 cursor-pointer">
                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            <p class="mt-2 text-sm text-gray-600">Add More Images (max 5 total)</p>
                            <input type="file" name="images[]" id="image-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" multiple>
                        </div>
                    </div>
                    {{-- Image previews will be inserted here by JavaScript --}}
                </div>
                <p class="text-xs text-gray-500">Supports JPG, PNG up to 5MB each. If new images are uploaded, the first new one will be set as featured unless an existing one is already featured.</p>
                @error('images') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('delete_images') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>


            <!-- Section 2: Basic Information -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Essential details about the vehicle.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Make (Brand) -->
                    <div>
                        <label for="make" class="block text-sm font-medium text-gray-700 mb-1">Make (Brand) *</label>
                        {{-- If you have dynamic brands from DB:
                        <select name="brand_id" ... > @foreach($brands as $brand) <option value="{{ $brand->id }}" {{ old('brand_id', $car->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option> @endforeach </select>
                        Else, for simple string field: --}}
                        <select name="make" id="make" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Make</option>
                            @php $commonMakes = ['Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes', 'Audi', 'Tesla']; @endphp
                            @foreach($commonMakes as $brandOption)
                            <option value="{{ $brandOption }}" {{ old('make', $car->make) == $brandOption ? 'selected' : '' }}>{{ $brandOption }}</option>
                            @endforeach
                            <option value="other" {{ !in_array(old('make', $car->make), $commonMakes) && old('make', $car->make) != '' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div id="custom-make-container" class="mt-2 {{ !in_array(old('make', $car->make), $commonMakes) && old('make', $car->make) != '' ? '' : 'hidden' }}">
                            <input type="text" name="custom_make" id="custom_make" value="{{ !in_array(old('make', $car->make), $commonMakes) ? old('custom_make', $car->make) : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Enter make name">
                        </div>
                        @error('make') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $car->model) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., Camry">
                        @error('model') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Name (Optional, if you have a distinct 'name' field separate from make/model) -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Display Name (Optional)</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $car->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., Red Camry XSE">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year *</label>
                        <input type="number" name="year" id="year" min="1990" max="{{ date('Y') + 1 }}" 
                               value="{{ old('year', $car->year) }}" 
                               placeholder="Enter Year" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <select name="color" id="color" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Color</option>
                             @php $commonColors = ['Black', 'White', 'Silver', 'Gray', 'Red', 'Blue', 'Green', 'Yellow', 'Orange', 'Brown']; @endphp
                            @foreach($commonColors as $colorOption)
                            <option value="{{ $colorOption }}" {{ old('color', $car->color) == $colorOption ? 'selected' : '' }}>{{ $colorOption }}</option>
                            @endforeach
                            <option value="custom" {{ !in_array(old('color', $car->color), $commonColors) && old('color', $car->color) != '' ? 'selected' : '' }}>Custom</option>
                        </select>
                         <div id="custom-color-container" class="mt-2 {{ !in_array(old('color', $car->color), $commonColors) && old('color', $car->color) != '' ? '' : 'hidden' }}">
                            <input type="text" name="custom_color" id="custom_color" value="{{ !in_array(old('color', $car->color), $commonColors) ? old('custom_color', $car->color) : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Enter color name">
                        </div>
                        @error('color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- License Plate -->
                    <div>
                        <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">License Plate *</label>
                        <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate', $car->license_plate) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 uppercase"
                               placeholder="e.g., ABC123" maxlength="10">
                        @error('license_plate') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- VIN -->
                    <div>
                        <label for="vin" class="block text-sm font-medium text-gray-700 mb-1">VIN *</label>
                        <input type="text" name="vin" id="vin" value="{{ old('vin', $car->vin) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 uppercase"
                               placeholder="17-character VIN" maxlength="17">
                        @error('vin') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Specifications -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Specifications</h3>
                    <p class="mt-1 text-sm text-gray-500">Technical details about the vehicle.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transmission -->
                    <div>
                        <label for="transmission" class="block text-sm font-medium text-gray-700 mb-1">Transmission *</label>
                        <select name="transmission" id="transmission" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Transmission</option>
                            <option value="manual" {{ old('transmission', $car->transmission) == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="automatic" {{ old('transmission', $car->transmission) == 'automatic' ? 'selected' : '' }}>Automatic</option>
                        </select>
                        @error('transmission') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Fuel Type -->
                    <div>
                        <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-1">Fuel Type *</label>
                        <select name="fuel_type" id="fuel_type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Fuel Type</option>
                            <option value="petrol" {{ old('fuel_type', $car->fuel_type) == 'petrol' ? 'selected' : '' }}>Petrol</option>
                            <option value="diesel" {{ old('fuel_type', $car->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="hybrid" {{ old('fuel_type', $car->fuel_type) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="electric" {{ old('fuel_type', $car->fuel_type) == 'electric' ? 'selected' : '' }}>Electric</option>
                        </select>
                        @error('fuel_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Seats -->
                    <div>
                        <label for="seats" class="block text-sm font-medium text-gray-700 mb-1">Number of Seats *</label>
                        <select name="seats" id="seats" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Seats</option>
                            @for($i = 2; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('seats', $car->seats) == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'seat' : 'seats' }}</option>
                            @endfor
                            <option value="9+" {{ old('seats', $car->seats) == '9+' ? 'selected' : '' }}>9+ seats</option>
                        </select>
                        @error('seats') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Doors -->
                    <div>
                        <label for="doors" class="block text-sm font-medium text-gray-700 mb-1">Number of Doors *</label>
                        <select name="doors" id="doors" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Doors</option>
                            @for($i = 2; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('doors', $car->doors) == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'door' : 'doors' }}</option>
                            @endfor
                        </select>
                        @error('doors') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Mileage -->
                    <div>
                        <label for="mileage" class="block text-sm font-medium text-gray-700 mb-1">Mileage (km) *</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" name="mileage" id="mileage" value="{{ old('mileage', $car->mileage) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 pl-16"
                                   placeholder="e.g., 15000" min="0">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">km</span>
                            </div>
                        </div>
                        @error('mileage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Features</h3>
                    <p class="mt-1 text-sm text-gray-500">Select the features available in this vehicle.</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @php $carFeatures = old('features', $car->features->pluck('id')->toArray()); @endphp
                    @foreach($features as $feature)
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="feature-{{ $feature->id }}" name="features[]" type="checkbox" value="{{ $feature->id }}" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                       {{ in_array($feature->id, $carFeatures) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="feature-{{ $feature->id }}" class="font-medium text-gray-700">{{ $feature->name }}</label>
                                @if($feature->description) <p class="text-gray-500">{{ $feature->description }}</p> @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('features') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Section 4: Rental Information -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Rental Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Details about pricing and availability.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Price per Day -->
                    <div>
                        <label for="price_per_day" class="block text-sm font-medium text-gray-700 mb-1">Price per Day ($) *</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day', $car->price_per_day) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 pl-8"
                                   placeholder="0.00" min="0" step="0.01">
                        </div>
                        @error('price_per_day') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="rented" {{ old('status', $car->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                            <option value="maintenance" {{ old('status', $car->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="out_of_service" {{ old('status', $car->status) == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Is Featured -->
                    <div class="flex items-center md:col-span-2"> {{-- Spanning 2 cols to align it or place it better --}}
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                               {{ old('is_featured', $car->is_featured) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-700">Featured Vehicle</label>
                    </div>
                </div>
            </div>

            <!-- Section 5: Description -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Description</h3>
                    <p class="mt-1 text-sm text-gray-500">Provide detailed information about the vehicle.</p>
                </div>
                
                <div>
                    <textarea name="description" id="description" rows="5" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe the vehicle's features, condition, special equipment, and any notable characteristics...">{{ old('description', $car->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                <a href="{{ route('admin.cars.show', $car->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" /></svg>
                    Update Vehicle
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript for image preview and custom select --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageUploadContainer = document.getElementById('image-upload-container');
    const fileInput = document.getElementById('image-input');
    const uploadBoxParent = document.getElementById('upload-box').parentElement;
    const uploadBoxItself = document.getElementById('upload-box');
    const maxImages = 5; // Total max images (existing + new)

    let dt = new DataTransfer(); // For newly uploaded files

    // Calculate how many new images can be uploaded
    const existingImageCount = document.querySelectorAll('.relative.group img[src^="{{ asset('storage/') }}"]').length;
    let uploadSlotsAvailable = maxImages - existingImageCount;

    function updateUploadBoxTextAndAvailability() {
        if (uploadSlotsAvailable <= 0 || dt.items.length >= uploadSlotsAvailable) {
            uploadBoxParent.style.display = 'none';
        } else {
            uploadBoxParent.style.display = 'block';
            const remainingSlots = uploadSlotsAvailable - dt.items.length;
            uploadBoxItself.querySelector('p').textContent = `Add More Images (${remainingSlots} slot${remainingSlots === 1 ? '' : 's'} left)`;
        }
    }


    function addFilesToDataTransfer(filesToAdd) {
        for (const file of filesToAdd) {
            if (!file.type.startsWith('image/')) continue;
            // Check against existing + new files count
            if ((existingImageCount + dt.items.length) < maxImages && !isFileInDt(file)) {
                dt.items.add(file);
            }
        }
        fileInput.files = dt.files;
        updateNewImagePreviews();
        updateUploadBoxTextAndAvailability();
    }

    function isFileInDt(file) {
        for (let i = 0; i < dt.items.length; i++) {
            const existingFile = dt.items[i].getAsFile();
            if (existingFile && existingFile.name === file.name &&
                existingFile.lastModified === file.lastModified &&
                existingFile.size === file.size) {
                return true;
            }
        }
        return false;
    }


    function updateNewImagePreviews() {
        const existingPreviews = imageUploadContainer.querySelectorAll('.new-image-preview');
        existingPreviews.forEach(el => el.remove());

        Array.from(dt.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative group new-image-preview h-40';

                previewDiv.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}" class="h-full w-full object-cover rounded-lg border border-gray-200">
                    <button type="button" data-filename="${file.name}" data-lastmodified="${file.lastModified}" data-filesize="${file.size}"
                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 focus:opacity-100 transition-opacity duration-200 shadow-md hover:bg-red-700"
                            title="Remove image">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    <span class="absolute bottom-1 left-1 bg-black bg-opacity-60 text-white text-xs px-1.5 py-0.5 rounded truncate max-w-[calc(100%-0.5rem)]" title="${file.name}">
                        ${file.name} (New)
                    </span>
                `;
                imageUploadContainer.insertBefore(previewDiv, uploadBoxParent);

                previewDiv.querySelector('button').addEventListener('click', function() {
                    const fileName = this.dataset.filename;
                    const fileLastModified = parseInt(this.dataset.lastmodified);
                    const fileSize = parseInt(this.dataset.filesize);

                    const newDt = new DataTransfer();
                    for (let i = 0; i < dt.items.length; i++) {
                        const f = dt.items[i].getAsFile();
                        if (f.name !== fileName || f.lastModified !== fileLastModified || f.size !== fileSize) {
                            newDt.items.add(f);
                        }
                    }
                    dt = newDt;
                    fileInput.files = dt.files;
                    updateNewImagePreviews();
                    updateUploadBoxTextAndAvailability();
                });
            };
            reader.readAsDataURL(file);
        });
    }


    fileInput.addEventListener('change', function(e) {
        addFilesToDataTransfer(e.target.files);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadBoxItself.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
             if ((existingImageCount + dt.items.length) < maxImages) {
                uploadBoxItself.classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadBoxItself.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
            uploadBoxItself.classList.remove('border-blue-500', 'bg-blue-50');
        });
    });

    uploadBoxItself.addEventListener('drop', function(e) {
        addFilesToDataTransfer(e.dataTransfer.files);
    });

    const makeSelect = document.getElementById('make');
    if (makeSelect) {
        makeSelect.addEventListener('change', function() {
            document.getElementById('custom-make-container').style.display = this.value === 'other' ? 'block' : 'none';
             if (this.value !== 'other') {
                document.getElementById('custom_make').value = ''; // Clear custom if not 'other'
            }
        });
        if (makeSelect.value === 'other') makeSelect.dispatchEvent(new Event('change'));
    }

    const colorSelect = document.getElementById('color');
    if (colorSelect) {
        colorSelect.addEventListener('change', function() {
            document.getElementById('custom-color-container').style.display = this.value === 'custom' ? 'block' : 'none';
            if (this.value !== 'custom') {
                document.getElementById('custom_color').value = ''; // Clear custom if not 'custom'
            }
        });
         if (colorSelect.value === 'custom') colorSelect.dispatchEvent(new Event('change'));
    }
    
    updateUploadBoxTextAndAvailability(); // Initial call
});
</script>
@endsection