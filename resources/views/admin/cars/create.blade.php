{{-- resources/views/admin/cars/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add New Car')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/admin/cars" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0M9 17h6"></path>
                    </svg>
                    Cars
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Add New Car</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Add New Car</h1>
        <p class="text-gray-600 mt-1">Fill in the details to add a new car to the system</p>
    </div>

    <!-- Form -->
<div class="bg-white rounded-lg shadow-md">
    <form action="/admin/cars" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
        @csrf
        
  
   <!-- Vehicle Images Section -->
<div class="space-y-6">
    <div class="border-b border-gray-200 pb-4">
        <h3 class="text-lg font-medium text-gray-900">Vehicle Images</h3>
        <p class="mt-1 text-sm text-gray-500">Upload high-quality photos of the vehicle (max 5 images).</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="image-upload-container">
        <!-- Upload Box -->
        <div class="relative group"> {{-- This div wraps the upload box itself --}}
            <div
                id="upload-box"
                class="h-40 border-2 border-dashed border-gray-300 rounded-lg flex flex-col justify-center items-center hover:border-blue-500 transition duration-200 bg-gray-50 cursor-pointer"
            >
                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="mt-2 text-sm text-gray-600">Click or Drag & Drop Images (max 5)</p>
               <input
                    type="file"
                    name="images[]"
                    id="image-input"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    accept="image/*"
                    multiple
                >
            </div>
        </div>
        {{-- Image previews will be inserted here by JavaScript before the upload-box's parent --}}
    </div>

    <p class="text-xs text-gray-500">Supports JPG, PNG up to 5MB each. First image will be used as featured.</p>

    @error('images')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error('images.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
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
                    <select name="make" id="make" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Make</option>
                        @foreach(['Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes', 'Audi', 'Tesla'] as $brand)
                        <option value="{{ $brand }}" {{ old('make') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                        @endforeach
                        <option value="other">Other</option>
                    </select>
                    <div id="custom-make-container" class="mt-2 hidden">
                        <input type="text" name="custom_make" id="custom_make" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Enter make name">
                    </div>
                    @error('make')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                    <input type="text" name="model" id="model" value="{{ old('model') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Camry">
                    @error('model')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Year -->
                                <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year *</label>
                        <input 
                            type="number" 
                            name="year" 
                            id="year" 
                            min="1990" 
                            max="{{ date('Y') + 1 }}" 
                            value="{{ old('year') ?? date('Y') }}" 
                            placeholder="Enter Year" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                
                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <select name="color" id="color" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Color</option>
                        @foreach(['Black', 'White', 'Silver', 'Gray', 'Red', 'Blue', 'Green', 'Yellow', 'Orange', 'Brown'] as $color)
                        <option value="{{ $color }}" {{ old('color') == $color ? 'selected' : '' }}>{{ $color }}</option>
                        @endforeach
                        <option value="custom">Custom</option>
                    </select>
                    <div id="custom-color-container" class="mt-2 hidden">
                        <input type="text" name="custom_color" id="custom_color" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Enter color name">
                    </div>
                </div>
                
                <!-- License Plate -->
                <div>
                    <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">License Plate *</label>
                    <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 uppercase"
                           placeholder="e.g., ABC123" maxlength="10">
                    @error('license_plate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- VIN -->
                <div>
                    <label for="vin" class="block text-sm font-medium text-gray-700 mb-1">VIN *</label>
                    <input type="text" name="vin" id="vin" value="{{ old('vin') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 uppercase"
                           placeholder="17-character VIN" maxlength="17">
                    @error('vin')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                        <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                        
                    </select>
                    @error('transmission')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Fuel Type -->
                <div>
                    <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-1">Fuel Type *</label>
                    <select name="fuel_type" id="fuel_type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Fuel Type</option>
                        <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>Electric</option>
                 
                    </select>
                    @error('fuel_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Seats -->
                <div>
                    <label for="seats" class="block text-sm font-medium text-gray-700 mb-1">Number of Seats *</label>
                    <select name="seats" id="seats" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Seats</option>
                        @for($i = 2; $i <= 8; $i++)
                        <option value="{{ $i }}" {{ old('seats') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'seat' : 'seats' }}</option>
                        @endfor
                        <option value="9+" {{ old('seats') == '9+' ? 'selected' : '' }}>9+ seats</option>
                    </select>
                    @error('seats')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Doors -->
                <div>
                    <label for="doors" class="block text-sm font-medium text-gray-700 mb-1">Number of Doors *</label>
                    <select name="doors" id="doors" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Doors</option>
                        @for($i = 2; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('doors') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'door' : 'doors' }}</option>
                        @endfor
                    </select>
                    @error('doors')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Mileage -->
                <div>
                    <label for="mileage" class="block text-sm font-medium text-gray-700 mb-1">Mileage (km) *</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" name="mileage" id="mileage" value="{{ old('mileage') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 pl-16"
                               placeholder="e.g., 15000" min="0">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">km</span>
                        </div>
                    </div>
                    @error('mileage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
        @foreach($features as $feature)
            <div class="relative flex items-start">
                <div class="flex items-center h-5">
                    <input 
                        id="feature-{{ $feature->id }}" 
                        name="features[]" 
                        type="checkbox" 
                        value="{{ $feature->id }}" 
                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                        {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}
                    >
                </div>
                <div class="ml-3 text-sm">
                    <label for="feature-{{ $feature->id }}" class="font-medium text-gray-700">
                        {{ $feature->name }}
                    </label>
                    @if($feature->description)
                        <p class="text-gray-500">{{ $feature->description }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    
    @error('features')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
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
                        <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 pl-8"
                               placeholder="0.00" min="0" step="0.01">
                    </div>
                    @error('price_per_day')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="out_of_service" {{ old('status') == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Is Featured -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
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
                          placeholder="Describe the vehicle's features, condition, special equipment, and any notable characteristics...">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        

        <!-- Form Actions -->
        <div class="flex items-center justify-between pt-8 border-t border-gray-200">
            <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Cancel
            </button>
            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Save Vehicle
            </button>
        </div>
    </form>
</div>


</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageUploadContainer = document.getElementById('image-upload-container');
    const fileInput = document.getElementById('image-input'); // The hidden file input
    const uploadBoxParent = document.getElementById('upload-box').parentElement; // The div wrapping the upload box
    const uploadBoxItself = document.getElementById('upload-box'); // The actual clickable/droppable area
    const maxImages = 5;

    let dt = new DataTransfer(); // Our single source of truth for files

    function createFileListItem(file) {
        // Helper to check if a file already exists in dt.items
        // Comparing by name, lastModified, and size for better uniqueness
        for (let i = 0; i < dt.items.length; i++) {
            const existingFile = dt.items[i].getAsFile();
            if (existingFile && existingFile.name === file.name &&
                existingFile.lastModified === file.lastModified &&
                existingFile.size === file.size) {
                return true; // File exists
            }
        }
        return false; // File does not exist
    }

    function addFilesToDataTransfer(filesToAdd) {
        for (const file of filesToAdd) {
            if (!file.type.startsWith('image/')) continue; // Only allow image types
            if (dt.items.length < maxImages && !createFileListItem(file)) {
                dt.items.add(file);
            }
        }
        // CRITICAL: Synchronize the actual file input with our DataTransfer object
        fileInput.files = dt.files;
        updatePreviewsAndUploadBox();
    }

    function updatePreviewsAndUploadBox() {
        // 1. Clear existing previews (but not the upload box itself initially)
        const existingPreviews = imageUploadContainer.querySelectorAll('.image-preview');
        existingPreviews.forEach(el => el.remove());

        // 2. Render new previews from dt.files
        Array.from(dt.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative group image-preview h-40'; // Ensure consistent height

                previewDiv.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}" class="h-full w-full object-cover rounded-lg border border-gray-200">
                    <button type="button" data-filename="${file.name}" data-lastmodified="${file.lastModified}" data-filesize="${file.size}"
                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 focus:opacity-100 transition-opacity duration-200 shadow-md hover:bg-red-700"
                            title="Remove image">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <span class="absolute bottom-1 left-1 bg-black bg-opacity-60 text-white text-xs px-1.5 py-0.5 rounded truncate max-w-[calc(100%-0.5rem)]" title="${file.name}">
                        ${file.name}
                    </span>
                `;
                // Insert previews before the upload box's parent div
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
                    dt = newDt; // Update our source of truth
                    fileInput.files = dt.files; // Synchronize the actual file input
                    updatePreviewsAndUploadBox(); // Re-render
                });
            };
            reader.readAsDataURL(file);
        });

        // 3. Control visibility/state of the upload box
        if (dt.files.length >= maxImages) {
            uploadBoxParent.style.display = 'none'; // Hide the upload box div if max images reached
        } else {
            uploadBoxParent.style.display = 'block'; // Show it otherwise
        }
    }

    // Event listener for the hidden file input
    fileInput.addEventListener('change', function(e) {
        addFilesToDataTransfer(e.target.files);
        // DO NOT RESET: e.target.value = ''; // This was the original problem!
    });

    // Drag & drop functionality for the visible uploadBoxItself
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadBoxItself.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
            if (dt.items.length < maxImages) {
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
        // e.dataTransfer.files contains the dropped files
        addFilesToDataTransfer(e.dataTransfer.files);
    });

    // Make/Color Custom Input Toggle (Keep this if you have it elsewhere)
    const makeSelect = document.getElementById('make');
    if (makeSelect) {
        makeSelect.addEventListener('change', function() {
            const customMakeContainer = document.getElementById('custom-make-container');
            customMakeContainer.style.display = this.value === 'other' ? 'block' : 'none';
        });
        // Trigger change on load if 'other' is pre-selected by old('make')
        if (makeSelect.value === 'other') {
            makeSelect.dispatchEvent(new Event('change'));
        }
    }


    const colorSelect = document.getElementById('color');
    if (colorSelect) {
        colorSelect.addEventListener('change', function() {
            const customColorContainer = document.getElementById('custom-color-container');
            customColorContainer.style.display = this.value === 'custom' ? 'block' : 'none';
        });
        // Trigger change on load if 'custom' is pre-selected by old('color')
        if (colorSelect.value === 'custom') {
            colorSelect.dispatchEvent(new Event('change'));
        }
    }


    // Initial call to set up previews (e.g., if there are `old('images')` - though handling `old()` for files is tricky)
    updatePreviewsAndUploadBox();
});
</script>


@endsection