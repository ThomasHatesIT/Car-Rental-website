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
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Available Cars</h2>
                <div class="flex space-x-4">
                                      <button class="car-swiper-prev p-3 rounded-full bg-blue-600 shadow-lg hover:bg-blue-700 transition duration-300 cursor-pointer">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
    </svg>
</button>
<button class="car-swiper-next p-3 rounded-full bg-blue-600 shadow-lg hover:bg-blue-700 transition duration-300 cursor-pointer">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
</button>


                </div>
            </div>

            <!-- Swiper Carousel -->
            <div class="swiper car-swiper">
                <div class="swiper-wrapper pb-10">
                    @foreach ($cars as $car)
                    <div class="swiper-slide">
                        <div class="h-full bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col">
                            <div class="w-full h-48 overflow-hidden">
                                    @if ($car->images && $car->images->isNotEmpty())
                                  <img
                    src="{{ asset('storage/' . $car->images->first()->path) }}" {{-- Get path from first CarImage object --}}
                    alt="{{ $car->make }} {{ $car->model }}"
                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                >  @else
                {{-- Placeholder image --}}
                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {{-- SVG Path --}}
                    </svg>
                </div>
            @endif
                            </div>
                            <div class="p-6 flex-grow flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        {{ $car->make }} {{ $car->model }}
                                    </h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $car->type }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">
                                    {{ $car->year }} • {{ ucfirst($car->color) }} • {{ $car->transmission }}
                                </p>
                                <div class="flex items-center mb-4">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 {{ $i < $car->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                    <span class="text-gray-600 text-xs ml-1">({{ $car->reviews_count }})</span>
                                </div>
                                <p class="text-gray-700 text-sm mb-4 flex-grow">
                                    {{ Str::limit($car->description, 80) }}
                                </p>
                                <div class="flex justify-between items-center mt-auto">
                                    <p class="text-lg font-bold text-blue-600">
                                        ${{ number_format($car->price_per_day, 2) }} <span class="text-sm font-normal text-gray-500">/day</span>
                                    </p>
                                    <div class="flex gap-2">
                                        <a href="/car/{{$car->id}}" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">Details</a>
                                        <a href="" class="px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>


            <!-- Why Choose Us Section -->
<div class="mt-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-gray-900 mb-10 pl-4 md:pl-0 text-left">Why Choose Us?</h2>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Card 1 -->
      <div class="bg-white rounded-xl shadow-lg p-6 min-h-[320px] flex flex-col">
            <div class="mb-4 w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">Flexible Rentals</h3>
            <p class="text-gray-600">Book for a day, week, or month—choose what fits your plans.</p>
        </div>

        <!-- Card 2 -->
     <div class="bg-white rounded-xl shadow-lg p-6 min-h-[320px] flex flex-col">
            <div class="mb-4 w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0M9 17h6" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">Trusted Service</h3>
            <p class="text-gray-600">Thousands of happy customers and reliable support.</p>
        </div>

        <!-- Card 3 -->
  <div class="bg-white rounded-xl shadow-lg p-6 min-h-[320px] flex flex-col">
            <div class="mb-4 w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">Transparent Pricing</h3>
            <p class="text-gray-600">No hidden fees. See your total before you book.</p>
        </div>
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

    // Initialize Swiper
    const carSwiper = new Swiper('.car-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.car-swiper-next',
            prevEl: '.car-swiper-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        }
    });
});
</script>
@endsection