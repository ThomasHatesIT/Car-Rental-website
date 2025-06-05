{{-- resources/views/components/car-details-card.blade.php --}}
@props([
    'car',
    // 'backText' => 'Back', // Removed as back button is outside this component now
])

<!-- Car Details Card Content -->
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
                     class="w-full h-full object-cover">
            </div>
        @else
            <div class="aspect-w-16 aspect-h-9 bg-gray-300 flex items-center justify-center">
                <svg class="w-20 h-20 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif

        @if($car->is_featured)
            <div class="absolute top-4 left-4">
                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                    Featured
                </span>
            </div>
        @endif

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

    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start mb-6">
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

        @if($car->description)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                <p class="text-gray-700 leading-relaxed">{{ $car->description }}</p>
            </div>
        @endif

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

        @if($car->images && $car->images->count() > 1)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">More Photos</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($car->images->slice(1) as $imageModel)
                        <div class="aspect-w-1 aspect-h-1">
                            <img src="{{ asset('storage/' . $imageModel->path) }}"
                                 alt="{{ $car->make }} {{ $car->model }} - Additional Photo"
                                 class="w-full h-full object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Actions Section -->
        <div class="border-t pt-6 mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{-- This heading can also be made a slot if needed, or passed as a prop --}}
                        @auth
                            @can('edit cars')
                                Manage this listing
                            @else
                                Ready to book this car?
                            @endcan
                        @else
                             Ready to book this car?
                        @endauth
                    </h3>
                    <p class="text-gray-600">Available for ${{ number_format($car->price_per_day, 2) }} per day</p>
                </div>

                {{-- THIS IS WHERE THE SLOT GOES --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 mt-4 sm:mt-0">
                    {{ $actions ?? '' }} {{-- 'actions' is the name of our slot --}}
                </div>
            </div>
        </div>
    </div>
</div>