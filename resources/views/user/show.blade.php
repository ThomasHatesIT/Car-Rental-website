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
            <!-- Main Car Image -->
            <div class="relative">
                @if($car->images->isNotEmpty())
                    @php
                        $mainDisplayImage = $car->images->first();
                    @endphp
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img src="{{ asset('storage/' . $mainDisplayImage->path) }}"
                             alt="{{ $car->make }} {{ $car->model }} - Main View"
                             class="w-full h-full object-cover"> {{-- h-full ensures it fills the aspect ratio container --}}
                    </div>
                @else
                    {{-- Placeholder if no images --}}
                    <div class="aspect-w-16 aspect-h-9 bg-gray-300 flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif

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
             @if($car->features->isNotEmpty())
    <div class="mb-10">
        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Car Features</h3>
        <div class="bg-white shadow-md rounded-lg p-6">
            <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-gray-700">
                @foreach($car->features as $feature)
                    <li class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ $feature->name }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif


                <!-- Additional Images (Gallery) -->
                @if($car->images && $car->images->count() > 1)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">More Photos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {{-- Slice the collection to get all images *except the first one* --}}
                            @foreach($car->images->slice(1) as $imageModel)
                                <div class="aspect-w-1 aspect-h-1"> {{-- Or aspect-square. Requires @tailwindcss/aspect-ratio --}}
                                    <img src="{{ asset('storage/' . $imageModel->path) }}"
                                         alt="{{ $car->make }} {{ $car->model }} - Additional Photo"
                                         class="w-full h-full object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                                        <!-- Book Now Section -->
                                <div class="border-t pt-6 mt-6">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-900">Ready to book this car?</h3>
                                            <p class="text-gray-600">Available for ${{ number_format($car->price_per_day, 2) }} per day</p>
                                        </div>
                                  @can('edit cars')
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mt-4 sm:mt-0">
                                            {{-- Edit Button - Authorization and proper route to be added later --}}
                                            <a href="{{route('cars.edit', $car)}}" {{-- Placeholder link - Replace with your actual edit route later --}}
                                            class="inline-flex items-center justify-center px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 order-last sm:order-first sm:mr-3 w-full sm:w-auto">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit Car
                                            </a>
                                    @endcan
                                            {{-- End Edit Button --}}

                                            @if($car->status === 'available')
                                                <a href="{{route('bookings.create', $car->id)}}" {{-- Placeholder link - Add your booking route here --}}
                                                class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Book Now
                                                </a>
                                            @else
                                                <button disabled
                                                        class="inline-flex items-center justify-center px-8 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed w-full sm:w-auto">
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
</div>
@endsection