
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
            
            <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
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
document.addEventListener('DOMContentLoaded', function () {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const dailyRateInput = {{ $car->price_per_day }}; // Direct number
    const dailyRate = parseFloat(dailyRateInput);
    const taxRate = 0.10; // 10%

    const totalDaysEl = document.getElementById('total-days');
    const subtotalEl = document.getElementById('subtotal');
    const taxAmountEl = document.getElementById('tax-amount');
    const totalAmountEl = document.getElementById('total-amount');

    function calculateSummary() {
        const startDateVal = startDateInput.value;
        const endDateVal = endDateInput.value;

        if (startDateVal && endDateVal && dailyRate) {
            // Create Date objects at UTC midnight to avoid timezone issues for simple day diff
            const start = new Date(startDateVal + 'T00:00:00Z');
            const end = new Date(endDateVal + 'T00:00:00Z');

            if (isNaN(start.getTime()) || isNaN(end.getTime())) { // Check for invalid dates
                clearSummary();
                return;
            }
            
            if (end < start) {
                clearSummary(); // Or show an error message like "Return date cannot be before pickup date"
                // Optionally disable submit button
                return;
            }

            // Calculate difference in days. Add 1 because rental for same day is 1 day.
            const diffTime = end.getTime() - start.getTime(); // Difference in milliseconds
            let calculatedDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            // Ensure days are at least 1 if dates are valid and end >= start
            // If start and end are the same, diffTime is 0, calculatedDays is 1. Correct.
            // If end is next day, diffTime is 1 day in ms, calculatedDays is 2. This is where it differs from backend.
            // Let's adjust to match backend logic: diffInDays + 1
            // For JS, if start=26th, end=26th -> 0 days diff + 1 = 1 day
            // If start=26th, end=27th -> 1 day diff + 1 = 2 days
            // This matches the Carbon diffInDays($start)->addDay() or diffInDays($start)+1 logic

            // A more direct day difference logic:
            // Number of days = (endDate - startDate) / millisecondsInDay.
            // For inclusive count: floor((endDate - startDate) / msPerDay) + 1
            // For Carbon's diffInDays behavior, it's the number of full 24-hour periods.
            // Let's stick to the time difference and add 1 for inclusive display.
            // This is how many calendar days are spanned.

            // Re-evaluating the day calculation for client-side to match PHP:
            // Carbon's $endDate->diffInDays($startDate) counts full 24-hour periods.
            // So, 2023-10-26 to 2023-10-26 is 0 days. +1 = 1 day.
            // 2023-10-26 to 2023-10-27 is 1 day. +1 = 2 days.

            // JS equivalent:
            // Treat dates as just dates, ignore time for day counting.
            const sDate = new Date(startDateVal); // Local timezone, but just for day part
            const eDate = new Date(endDateVal);
            
            // Reset time to midnight for consistent day difference calculation
            sDate.setHours(0,0,0,0);
            eDate.setHours(0,0,0,0);

            if (eDate < sDate) { // Double check after parsing
                 clearSummary();
                 return;
            }

            const daysDiff = Math.round((eDate - sDate) / (1000 * 60 * 60 * 24));
            calculatedDays = daysDiff + 1;


            if (calculatedDays > 0) {
                const subtotal = dailyRate * calculatedDays;
                const tax = subtotal * taxRate;
                const total = subtotal + tax;

                totalDaysEl.textContent = calculatedDays;
                subtotalEl.textContent = '$' + subtotal.toFixed(2);
                taxAmountEl.textContent = '$' + tax.toFixed(2);
                totalAmountEl.textContent = '$' + total.toFixed(2);
            } else {
                clearSummary();
            }
        } else {
            clearSummary();
        }
    }

    function clearSummary() {
        totalDaysEl.textContent = '-';
        subtotalEl.textContent = '$0.00';
        taxAmountEl.textContent = '$0.00';
        totalAmountEl.textContent = '$0.00';
    }

    startDateInput.addEventListener('change', function() {
        if (this.value) {
            endDateInput.min = this.value;
            // If end_date was before new start_date, clear or adjust end_date
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value; // Set end_date to be same as start_date
            }
        } else {
            // Reset to today if start_date is cleared, also clear end_date's value
            endDateInput.min = "{{ date('Y-m-d') }}";
            // endDateInput.value = ""; // Optionally clear end_date if start_date is cleared
        }
        calculateSummary();
    });

    endDateInput.addEventListener('change', function() {
        // Ensure min is still respected if start_date wasn't changed but end_date is manually typed
        if (startDateInput.value && this.value < startDateInput.value) {
            this.value = startDateInput.value; // Correct it
        }
        calculateSummary();
    });

    // Initial calculation on page load if dates are pre-filled (e.g., by old() helper)
    // And ensure end_date.min is set correctly if old('start_date') is present
    if (startDateInput.value) {
        endDateInput.min = startDateInput.value;
    }
    calculateSummary();

});
</script>

@endsection
