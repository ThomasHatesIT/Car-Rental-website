
@extends('layouts.app')

@section('title', 'Book Car - ' . $car->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Car Summary -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/3">
                    {{-- In your booking create view --}}
                            @if($car->featuredImage)  {{-- <--- Changed to camelCase: featuredImage --}}
                                <img src="{{ Storage::url($car->featuredImage->path) }}" 
                                    alt="{{ $car->name ?? $car->make . ' ' . $car->model }}" {{-- Added a fallback for alt text --}}
                                    class="w-full h-48 object-cover rounded-lg">
                            @elseif($car->images->isNotEmpty()) {{-- Fallback to first image if no featuredImage --}}
                                <img src="{{ Storage::url($car->images->first()->path) }}"
                                    alt="{{ $car->name ?? $car->make . ' ' . $car->model }}"
                                    class="w-full h-48 object-cover rounded-lg">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500">No Image Available</span>
                                </div>
                            @endif
                </div>
                <div class="md:w-2/3">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $car->name }}</h1>
                    <p class="text-gray-600 mb-2">{{ $car->make }} {{ $car->model }} ({{ $car->year }})</p>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium">Transmission:</span> {{ ucfirst($car->transmission) }}
                        </div>
                        <div>
                            <span class="font-medium">Fuel Type:</span> {{ ucfirst($car->fuel_type) }}
                        </div>
                        <div>
                            <span class="font-medium">Seats:</span> {{ $car->seats }}
                        </div>
                        <div>
                            <span class="font-medium">Doors:</span> {{ $car->doors }}
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($car->price_per_day, 2) }}</span>
                        <span class="text-gray-600">/day</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Booking Details</h2>
            
            <form action="" method="POST">
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dates -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Pickup Date *
                        </label>
                        <input type="date" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ old('start_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror"
                               required>
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Return Date *
                        </label>
                        <input type="date" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ old('end_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror"
                               required>
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Times -->
                    <div>
                        <label for="pickup_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Pickup Time *
                        </label>
                        <input type="time" 
                               id="pickup_time" 
                               name="pickup_time" 
                               value="{{ old('pickup_time', '09:00') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pickup_time') border-red-500 @enderror"
                               required>
                        @error('pickup_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="return_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Return Time *
                        </label>
                        <input type="time" 
                               id="return_time" 
                               name="return_time" 
                               value="{{ old('return_time', '17:00') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('return_time') border-red-500 @enderror"
                               required>
                        @error('return_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Locations -->
                    <div>
                        <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">
                            Pickup Location *
                        </label>
                        <select id="pickup_location" 
                                name="pickup_location" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pickup_location') border-red-500 @enderror"
                                required>
                            <option value="">Select pickup location</option>
                            <option value="Downtown Office" {{ old('pickup_location') == 'Downtown Office' ? 'selected' : '' }}>Downtown Office</option>
                            <option value="Airport Terminal" {{ old('pickup_location') == 'Airport Terminal' ? 'selected' : '' }}>Airport Terminal</option>
                            <option value="Mall Location" {{ old('pickup_location') == 'Mall Location' ? 'selected' : '' }}>Mall Location</option>
                            <option value="Hotel Delivery" {{ old('pickup_location') == 'Hotel Delivery' ? 'selected' : '' }}>Hotel Delivery</option>
                        </select>
                        @error('pickup_location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="return_location" class="block text-sm font-medium text-gray-700 mb-2">
                            Return Location *
                        </label>
                        <select id="return_location" 
                                name="return_location" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('return_location') border-red-500 @enderror"
                                required>
                            <option value="">Select return location</option>
                            <option value="Downtown Office" {{ old('return_location') == 'Downtown Office' ? 'selected' : '' }}>Downtown Office</option>
                            <option value="Airport Terminal" {{ old('return_location') == 'Airport Terminal' ? 'selected' : '' }}>Airport Terminal</option>
                            <option value="Mall Location" {{ old('return_location') == 'Mall Location' ? 'selected' : '' }}>Mall Location</option>
                            <option value="Hotel Delivery" {{ old('return_location') == 'Hotel Delivery' ? 'selected' : '' }}>Hotel Delivery</option>
                        </select>
                        @error('return_location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Special Notes/Requests
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                              placeholder="Any special requests or notes...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Booking Summary -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Summary</h3>
                    <div id="booking-summary">
                        <div class="flex justify-between mb-2">
                            <span>Daily Rate:</span>
                            <span>${{ number_format($car->price_per_day, 2) }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Number of Days:</span>
                            <span id="total-days">-</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Tax (10%):</span>
                            <span id="tax-amount">$0.00</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total Amount:</span>
                            <span id="total-amount">$0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between mt-8">
                    <a href="{{ route('cars.show', $car) }}" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Back to Car Details
                    </a>
                    <button type="submit" 
                            class="px-8 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                       Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const dailyRate = {{ $car->price_per_day }};

    function calculateBooking() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDate && endDate && endDate > startDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            const subtotal = totalDays * dailyRate;
            const taxAmount = subtotal * 0.10;
            const totalAmount = subtotal + taxAmount;
            
            document.getElementById('total-days').textContent = totalDays;
            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('tax-amount').textContent = '$' + taxAmount.toFixed(2);
            document.getElementById('total-amount').textContent = '$' + totalAmount.toFixed(2);
        } else {
            document.getElementById('total-days').textContent = '-';
            document.getElementById('subtotal').textContent = '$0.00';
            document.getElementById('tax-amount').textContent = '$0.00';
            document.getElementById('total-amount').textContent = '$0.00';
        }
    }

    startDateInput.addEventListener('change', calculateBooking);
    endDateInput.addEventListener('change', function() {
        this.min = startDateInput.value;
        calculateBooking();
    });
    
    // Initial calculation if dates are pre-filled
    calculateBooking();
});
</script>
@endsection
