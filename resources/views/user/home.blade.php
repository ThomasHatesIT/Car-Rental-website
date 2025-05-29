@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16">
        <!-- Main Heading -->
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Find Your Perfect Ride
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Search and book cars in seconds. Flexible rentals, trusted service.
            </p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-16">
            <form action="" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Car Type -->
                    <div class="space-y-2">
                        <label for="car_type" class="block text-sm font-medium text-gray-700">Car Type</label>
                        <div class="relative">
                            <select name="car_type" id="car_type" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white">
                                <option value="">SUV, Sedan, etc.</option>
                                <option value="sedan">Sedan</option>
                                <option value="suv">SUV</option>
                                <option value="hatchback">Hatchback</option>
                                <option value="electric">Electric</option>
                                <option value="luxury">Luxury</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0M9 17h6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pick-up Location -->
                    <div class="space-y-2">
                        <label for="pickup_location" class="block text-sm font-medium text-gray-700">Pick-up Location</label>
                        <div class="relative">
                            <input type="text" name="pickup_location" id="pickup_location" placeholder="Enter city or airport" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pick-up Date -->
                    <div class="space-y-2">
                        <label for="pickup_date" class="block text-sm font-medium text-gray-700">Pick-up Date</label>
                        <div class="relative">
                            <input type="date" name="pickup_date" id="pickup_date" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2h4z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Return Date -->
                    <div class="space-y-2">
                        <label for="return_date" class="block text-sm font-medium text-gray-700">Return Date</label>
                        <div class="relative">
                            <input type="date" name="return_date" id="return_date" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2h4z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Available Cars Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Available Cars</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Toyota Camry -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <!-- Car Icon -->
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0M9 17h6"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Trusted Service</h3>
                    <p class="text-gray-600">Thousands of happy customers and top-rated support.</p>
                </div>

                <!-- Flexible Rentals -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Flexible Rentals</h3>
                    <p class="text-gray-600">Book for a day, week, or month—your choice.</p>
                </div>

                <!-- Transparent Pricing -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Transparent Pricing</h3>
                    <p class="text-gray-600">No hidden fees. See your total before you book.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set minimum date to today for date inputs
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const pickupDate = document.getElementById('pickup_date');
    const returnDate = document.getElementById('return_date');
    
    pickupDate.setAttribute('min', today);
    returnDate.setAttribute('min', today);
    
    // Update return date minimum when pickup date changes
    pickupDate.addEventListener('change', function() {
        returnDate.setAttribute('min', this.value);
        if (returnDate.value && returnDate.value < this.value) {
            returnDate.value = this.value;
        }
    });
});
</script>
@endsection