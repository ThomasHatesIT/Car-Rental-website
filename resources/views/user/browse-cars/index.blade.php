@extends('layouts.app')

@section('title', 'Browse Our Fleet - Car Rental')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Browse Our Fleet</h1>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600">Find the car that's right for your next trip.</p>
        </div>

        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Filters Sidebar (col-span-3 on large screens) -->
            <aside class="lg:col-span-3">
                <form action="{{ route('browseCars.index') }}" method="GET" class="space-y-6 bg-white p-6 rounded-xl shadow-lg">
                    
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-3">Filters</h2>

                    <!-- Search Filter -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Keyword Search</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="e.g. Honda Civic, SUV">
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price Range (/day)</label>
                        <div class="mt-1 flex items-center space-x-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Min">
                            <span class="text-gray-500">-</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Max">
                        </div>
                    </div>

                    <!-- Transmission Filter -->
                    <div>
                        <label for="transmission" class="block text-sm font-medium text-gray-700">Transmission</label>
                        <select id="transmission" name="transmission" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="any" {{ request('transmission') == 'any' ? 'selected' : '' }}>Any</option>
                            <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>

                    <!-- Sort By Filter -->
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700">Sort By</label>
                        <select id="sort_by" name="sort_by" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="created_at_desc" {{ request('sort_by', 'created_at_desc') == 'created_at_desc' ? 'selected' : '' }}>Newest Listings</option>
                            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="year_desc" {{ request('sort_by') == 'year_desc' ? 'selected' : '' }}>Year: Newest First</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply Filters
                        </button>
                        <a href="{{ route('browseCars.index') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Reset Filters
                        </a>
                    </div>
                </form>
            </aside>

            <!-- Main Content: Car Grid (col-span-9 on large screens) -->
            <main class="mt-8 lg:mt-0 lg:col-span-9">
                <div class="mb-5 flex justify-between items-center">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ $cars->firstItem() ?? 0 }}</span>
                        to <span class="font-medium">{{ $cars->lastItem() ?? 0 }}</span> of
                        <span class="font-medium">{{ $cars->total() }}</span> results
                    </p>
                </div>
                
                @if ($cars->isNotEmpty())
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($cars as $car)
                            {{-- This card HTML is copied directly from your home page for consistency --}}
                            <div class="flex flex-col h-full"> {{-- Added flex-col and h-full to the wrapper for equal height --}}
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

                                        <div class="text-gray-700 text-sm mb-4 flex-grow relative overflow-hidden" style="height: 4.5rem;">
                                            <p class="absolute inset-0">{{ $car->description }}</p>
                                        </div>
                                        
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
                    
                    <!-- Pagination Links -->
                    <div class="mt-12">
                        {{ $cars->links() }}
                    </div>

                @else
                    <div class="text-center bg-white p-12 rounded-lg shadow-md">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-xl font-medium text-gray-900">No Cars Found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            We couldn't find any cars matching your criteria. Try adjusting your filters.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('browseCars.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Clear all filters
                            </a>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>
@endsection