{{-- resources/views/admin/cars/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Cars Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Cars Management</h1>
            <p class="text-gray-600 mt-1">Manage all cars in the system</p>
        </div>
      <a href="{{ route('admin.cars.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Car
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Cars</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $cars->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Available</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $cars->where('status', 'available')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Unavailable</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $cars->where('status', 'unavailable')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cars Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">All Cars</h2>
        </div>
        
        @if($cars->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specifications</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cars as $car)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                            @if($car->featuredImage)
    <img class="h-16 w-16 rounded-lg object-cover mr-4" 
         src="{{ asset('storage/' . $car->featuredImage->path) }}" 
         alt="{{ $car->model ?? 'Car image' }}">
@elseif($car->images->isNotEmpty()) {{-- Fallback to first image if no featured one --}}
    <img class="h-16 w-16 rounded-lg object-cover mr-4"
         src="{{ asset('storage/' . $car->images->first()->path) }}"
         alt="{{ $car->model ?? 'Car image' }}">
@else
    <div class="h-16 w-16 rounded-lg bg-gray-200 mr-4 flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    </div>
@endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $car->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $car->brand }} • {{ $car->year }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $car->model }}</div>
                            <div class="text-sm text-gray-500">{{ $car->fuel_type ?? 'N/A' }} • {{ $car->transmission ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${{ number_format($car->price_per_day ?? 0, 2) }}/day</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($car->status == 'available')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                            @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Unavailable</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                          <a href="{{ route('admin.cars.show', $car->id) }}"

                                   class="text-blue-600 hover:text-blue-900 p-1 rounded transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="" 
                                   class="text-indigo-600 hover:text-indigo-900 p-1 rounded transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="" method="POST" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this car?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded transition duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0M9 17h6"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No cars</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new car.</p>
            <div class="mt-6">
                <a href="" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Car
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection