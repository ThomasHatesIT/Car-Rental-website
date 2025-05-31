@extends('layouts.app')

@section('title', 'Find Your Perfect Ride - Car Rental') {{-- Added a more specific title --}}

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-100 to-blue-50"> {{-- Subtle gradient background --}}

    <!-- Hero Section -->
    <div class="relative text-center pt-20 pb-24 sm:pt-28 sm:pb-32 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
            {{-- Optional: A subtle background pattern or image could go here if desired
            <svg class="absolute inset-0 h-full w-full text-gray-200/30 transform -translate-x-1/2 left-1/2" fill="currentColor" viewBox="0 0 404 784">
                <defs><pattern id="hero-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" /></pattern></defs>
                <rect width="404" height="784" fill="url(#hero-pattern)" />
            </svg>
            --}}
        </div>
        <div class="relative max-w-3xl mx-auto">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-gray-900 tracking-tight">
                <span class="block">Find Your</span>
                <span class="block text-blue-600">Perfect Ride.</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-xl mx-auto">
                Explore our diverse fleet and book your next adventure with ease. Premium cars, unbeatable prices.
            </p>

            <!-- Simplified Search Form -->
            <form action="" method="GET" class="mt-10 max-w-xl mx-auto sm:flex sm:gap-4">
                <div class="flex-grow min-w-0">
                    <label for="search_keyword" class="sr-only">Search cars</label>
                    <input type="text" name="search" id="search_keyword"
                           value="{{ request('search') }}" {{-- Keep search term if page reloads --}}
                           class="w-full px-5 py-4 text-lg border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500"
                           placeholder="Search by make, model, type...">
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-3">
                    <button type="submit"
                            class="w-full sm:w-auto px-8 py-4 text-lg font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Search
                    </button>
                </div>
            </form>
            {{-- Optional: Quick links or popular categories
            <div class="mt-8 text-sm">
                <span class="text-gray-500">Popular:</span>
                <a href="?type=suv" class="ml-2 font-medium text-blue-600 hover:text-blue-500">SUVs</a>,
                <a href="?type=sedan" class="ml-2 font-medium text-blue-600 hover:text-blue-500">Sedans</a>,
                <a href="?type=electric" class="ml-2 font-medium text-blue-600 hover:text-blue-500">Electric</a>
            </div>
            --}}
        </div>
    </div>

    <!-- Featured Cars Section -->
    <div class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-10 sm:mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Featured Cars</h2>
                <div class="flex space-x-3">
                    <button class="car-swiper-prev p-3 rounded-full bg-white border border-gray-300 text-gray-600 shadow-sm hover:bg-gray-100 transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="car-swiper-next p-3 rounded-full bg-white border border-gray-300 text-gray-600 shadow-sm hover:bg-gray-100 transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            @if($cars->isNotEmpty())
            <div class="swiper car-swiper -mx-4 sm:mx-0"> {{-- Negative margin for full-bleed on mobile --}}
                <div class="swiper-wrapper pb-12"> {{-- Padding bottom for pagination --}}
                    @foreach ($cars as $car)
               {{-- Inside the @foreach ($cars as $car) loop --}}
                                    <div class="swiper-slide px-2 sm:px-0">
                                        <div class="h-full bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col group">
                                            <a href="{{ route('cars.show', $car->id) }}" class="block">
                                                <div class="w-full h-56 sm:h-64 overflow-hidden">
                                                    @if ($car->images && $car->images->isNotEmpty())
                                                        <img src="{{ asset('storage/' . $car->images->first()->path) }}"
                                                            alt="{{ $car->make }} {{ $car->model }}"
                                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                                    @else
                                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </a>
                                            <div class="p-5 sm:p-6 flex-grow flex flex-col">
                                                <div class="flex justify-between items-start mb-2">
                                                    <h3 class="text-xl font-semibold text-gray-900">
                                                        <a href="{{ route('cars.show', $car->id) }}" class="hover:text-blue-600 transition-colors">{{ $car->make }} {{ $car->model }}</a>
                                                    </h3>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-3">
                                                    {{ $car->year }} • {{ ucfirst($car->color ?? 'N/A') }} • {{ ucfirst($car->transmission ?? 'N/A') }}
                                                </p>

                                                {{-- MODIFICATION START --}}
                                                <div class="text-gray-700 text-sm mb-4 flex-grow relative overflow-hidden" style="height: 4.5rem;"> {{-- Adjust height as needed (e.g., 3 lines) --}}
                                                    <p class="absolute inset-0"> {{-- This p tag is for the actual text --}}
                                                        {{ $car->description }}
                                                    </p>
                                                    {{-- Optional: Add a fade-out effect if text overflows strongly --}}
                                                    {{-- <div class="absolute bottom-0 left-0 w-full h-6 bg-gradient-to-t from-white to-transparent pointer-events-none"></div> --}}
                                                </div>
                                                {{-- MODIFICATION END --}}

                                                <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-200">
                                                    <p class="text-xl font-bold text-blue-600">
                                                        ${{ number_format($car->price_per_day, 2) }}
                                                        <span class="text-sm font-normal text-gray-500">/day</span>
                                                    </p>
                                                    <a href="{{ route('cars.show', $car->id) }}" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination mt-8 text-center"></div>
            </div>
            @else
                <p class="text-center text-gray-600 text-lg">No featured cars available at the moment. Check back soon!</p>
            @endif
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="py-16 sm:py-20 bg-gradient-to-br from-gray-50 to-blue-50"> {{-- Consistent with hero bg --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-12 sm:mb-16 text-center">Why Rent With Us?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                @php
                    $perks = [
                        ['icon' => '<svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>', 'title' => 'Flexible Rentals', 'description' => 'Choose daily, weekly, or monthly plans. We adapt to your schedule.'],
                        ['icon' => '<svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>', 'title' => 'Quality Guaranteed', 'description' => 'Well-maintained, clean, and reliable vehicles for a smooth journey.'],
                        ['icon' => '<svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>', 'title' => 'Transparent Pricing', 'description' => 'No hidden fees. What you see is what you pay. Fair and simple.']
                    ];
                @endphp
                @foreach($perks as $perk)
                <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 text-center hover:shadow-xl transition-shadow duration-300">
                    <div class="mb-5 inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full">
                        {!! $perk['icon'] !!}
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $perk['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $perk['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper
    const carSwiper = new Swiper('.car-swiper', {
        slidesPerView: 1.2, // Show a bit of the next slide on mobile
        spaceBetween: 16,   // Space for mobile
        grabCursor: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.car-swiper-next',
            prevEl: '.car-swiper-prev',
        },
        breakpoints: {
            640: { // sm
                slidesPerView: 2,
                spaceBetween: 24,
            },
            1024: { // lg
                slidesPerView: 3,
                spaceBetween: 32,
            },
        }
    });
});
</script>
@endsection