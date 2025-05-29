@extends('layouts.app')

@section('title', $car->make . ' ' . $car->model . ' - Car Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="/" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Cars
            </a>
        </div>

        <!-- Car Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Car Images -->
            <div class="relative">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                    <img src="{{ asset('images/img1.jpg') }}" 
                         alt="{{ $car->make }} {{ $car->model }}" 
                         class="w-full h-96 object-cover">
                </div>
                    
                    <!-- Featured Badge -->
                    @if($car->is_featured)
                        <div class="absolute top-4 left-4">
                            <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Featured
                            </span>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($car->status === 'available') bg-green-500 text-white
                            @elseif($car->status === 'rented') bg-red-500 text-white
                            @elseif($car->status === 'maintenance') bg-yellow-500 text-white
                            @else bg-gray-500 text-white @endif">
                            {{ ucfirst(str_replace('_', ' ', $car->status)) }}
                        </span>
                    </div>
                </div>

            <!-- Car Details -->
            <div class="p-6">
                <!-- Title and Price -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            {{ $car->make }} {{ $car->model }}
                        </h1>
                        <p class="text-gray-600">{{ $car->year }} • {{ ucfirst($car->color) }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-blue-600">
                            ${{ number_format($car->price_per_day, 2) }}
                        </div>
                        <div class="text-gray-500">per day</div>
                    </div>
                </div>

                <!-- Description -->
                @if($car->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $car->description }}</p>
                    </div>
                @endif

                <!-- Car Specifications -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transmission:</span>
                                <span class="font-medium">{{ ucfirst($car->transmission) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Fuel Type:</span>
                                <span class="font-medium">{{ ucfirst($car->fuel_type) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Seats:</span>
                                <span class="font-medium">{{ $car->seats }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Doors:</span>
                                <span class="font-medium">{{ $car->doors }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mileage:</span>
                                <span class="font-medium">{{ number_format($car->mileage) }} miles</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">License Plate:</span>
                                <span class="font-medium">{{ $car->license_plate }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">VIN:</span>
                                <span class="font-medium text-xs">{{ $car->vin }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                @if($car->features)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Features</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $car->features }}</p>
                        </div>
                    </div>
                @endif

                <!-- Additional Images -->
                @if($car->images && count(json_decode($car->images)) > 1)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">More Photos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach(array_slice(json_decode($car->images), 1) as $image)
                                <div class="aspect-w-1 aspect-h-1">
                                    <img src="{{ asset('images/' . $image) }}" 
                                         alt="{{ $car->make }} {{ $car->model }}" 
                                         class="w-full h-32 object-cover rounded-lg">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Book Now Section -->
                <div class="border-t pt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Ready to book this car?</h3>
                            <p class="text-gray-600">Available for ${{ number_format($car->price_per_day, 2) }} per day</p>
                        </div>
                        
                        @if($car->status === 'available')
                            <a href="" 
                               class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Book Now
                            </a>
                        @else
                            <button disabled 
                                    class="inline-flex items-center justify-center px-8 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0h-2m9-5a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Not Available
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection