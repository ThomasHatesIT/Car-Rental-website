@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
{{-- Add any specific styles for this page if needed --}}
<style>
    .stat-card {
        @apply bg-white rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl;
    }
    .stat-card-icon-bg {
        @apply p-3 rounded-full mr-4;
    }
    .stat-card-title {
        @apply text-sm font-medium text-gray-500;
    }
    .stat-card-value {
        @apply text-2xl lg:text-3xl font-bold text-gray-800;
    }
    .section-title {
        @apply text-xl font-semibold text-gray-700 mb-4;
    }
    .table-action-link {
        @apply text-indigo-600 hover:text-indigo-900 font-medium;
    }
</style>
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Welcome banner -->
    <div class="relative bg-indigo-200 dark:bg-indigo-500 p-4 sm:p-6 rounded-xl overflow-hidden mb-8 shadow-lg">
        <!-- Background illustration -->
        <div class="absolute right-0 top-0 -mt-4 mr-16 pointer-events-none hidden xl:block" aria-hidden="true">
            <svg width="319" height="198" xmlns:xlink="http://www.w3.org/1999/xlink">
                <defs>
                    <path id="welcome-a" d="M64 0l64 128-64-20-64 20z" />
                    <path id="welcome-e" d="M40 0l40 80-40-12.5L0 80z" />
                    <path id="welcome-g" d="M40 0l40 80-40-12.5L0 80z" />
                    <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="welcome-b">
                        <stop stop-color="#A5B4FC" offset="0%" />
                        <stop stop-color="#818CF8" offset="100%" />
                    </linearGradient>
                    <linearGradient x1="50%" y1="24.537%" x2="50%" y2="100%" id="welcome-c">
                        <stop stop-color="#4338CA" offset="0%" />
                        <stop stop-color="#6366F1" stop-opacity="0" offset="100%" />
                    </linearGradient>
                </defs>
                <g fill="none" fill-rule="evenodd">
                    <g transform="rotate(64 36.592 105.604)">
                        <mask id="welcome-d" fill="#fff">
                            <use xlink:href="#welcome-a" />
                        </mask>
                        <use fill="url(#welcome-b)" xlink:href="#welcome-a" />
                        <path fill="url(#welcome-c)" mask="url(#welcome-d)" d="M64-24h80v152H64z" />
                    </g>
                    <g transform="rotate(-51 91.324 -105.372)">
                        <mask id="welcome-f" fill="#fff">
                            <use xlink:href="#welcome-e" />
                        </mask>
                        <use fill="url(#welcome-b)" xlink:href="#welcome-e" />
                        <path fill="url(#welcome-c)" mask="url(#welcome-f)" d="M40.333-15.147h50v152h-50z" />
                    </g>
                    <g transform="rotate(44 61.546 392.623)">
                        <mask id="welcome-h" fill="#fff">
                            <use xlink:href="#welcome-g" />
                        </mask>
                        <use fill="url(#welcome-b)" xlink:href="#welcome-g" />
                        <path fill="url(#welcome-c)" mask="url(#welcome-h)" d="M40.333-15.147h50v152h-50z" />
                    </g>
                </g>
            </svg>
        </div>
        <!-- Content -->
        <div class="relative">
            <h1 class="text-2xl md:text-3xl text-slate-800 dark:text-slate-100 font-bold mb-1">Good {{ date('A') == 'PM' && date('G') < 17 ? 'afternoon' : (date('A') == 'AM' ? 'morning' : 'evening') }}, {{ Auth::user()->name }} 👋</h1>
            <p class="dark:text-indigo-200">Here's what's happening with DriveNow today:</p>
        </div>
    </div>

    <!-- Key Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        <!-- Total Cars -->
        <div class="stat-card border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="stat-card-icon-bg bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="stat-card-title">Total Cars</p>
                    <p class="stat-card-value">{{ $totalCars ?? 0 }}</p>
                </div>
            </div>
        </div>
        <!-- Available Cars -->
        <div class="stat-card border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="stat-card-icon-bg bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="stat-card-title">Available Cars</p>
                    <p class="stat-card-value">{{ $availableCars ?? 0 }}</p>
                </div>
            </div>
        </div>
        <!-- Rented/Active Cars -->
        <div class="stat-card border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="stat-card-icon-bg bg-orange-100">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="stat-card-title">Cars Rented Out</p>
                    <p class="stat-card-value">{{ $rentedCarsCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        <!-- Total Users -->
        <div class="stat-card border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="stat-card-icon-bg bg-purple-100">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="stat-card-title">Total Users</p>
                    <p class="stat-card-value">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
        </div>
         <!-- Pending Bookings -->
        <div class="stat-card border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="stat-card-icon-bg bg-yellow-100">
                     <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="stat-card-title">Pending Approvals</p>
                    <p class="stat-card-value">{{ $pendingBookingsCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        <!-- Active Bookings -->
        <div class="stat-card border-l-4 border-teal-500">
            <div class="flex items-center">
                <div class="stat-card-icon-bg bg-teal-100">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="stat-card-title">Active Bookings</p>
                    <p class="stat-card-value">{{ $activeBookingsCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="section-title">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.cars.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg shadow-md text-center transition-colors duration-200 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add New Car
            </a>
            <a href="{{ route('admin.cars.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg shadow-md text-center transition-colors duration-200">Manage All Cars</a>
            <a href="{{-- route('admin.bookings.index') --}}" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-3 px-4 rounded-lg shadow-md text-center transition-colors duration-200 {{-- disabled:opacity-50 cursor-not-allowed --}}">Manage All Bookings</a>
            <a href="{{-- route('admin.users.index') --}}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg shadow-md text-center transition-colors duration-200 {{-- disabled:opacity-50 cursor-not-allowed --}}">Manage Users</a>
        </div>
         <p class="text-xs text-gray-500 mt-2">Note: 'Manage Bookings' and 'Manage Users' will be enabled once implemented.</p>
    </div>


    <!-- Recent Activity Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Latest Pending Bookings -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="section-title">Latest Pending Bookings</h2>
            @if(isset($latestPendingBookings) && $latestPendingBookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($latestPendingBookings as $booking)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->booking_number }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->user->name }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->car->make }} {{ $booking->car->model }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->start_date->format('M d') }} - {{ $booking->end_date->format('M d, Y') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    <a href="{{-- route('admin.bookings.show', $booking->id) --}}" class="table-action-link">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No pending bookings at the moment.</p>
            @endif
        </div>

        <!-- Cars Due for Return Soon -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="section-title">Cars Due For Return Soon</h2>
             @if(isset($carsDueForReturn) && $carsDueForReturn->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($carsDueForReturn as $booking)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->booking_number }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->car->make }} {{ $booking->car->model }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->user->name }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->end_date->format('M d, Y') }} {{ \Carbon\Carbon::parse($booking->return_time)->format('h:i A') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    <a href="{{-- route('admin.bookings.show', $booking->id) --}}" class="table-action-link">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No cars due for return soon.</p>
            @endif
        </div>

        <!-- Recently Added Cars -->
        <div class="bg-white rounded-xl shadow-lg p-6 lg:col-span-2"> {{-- Span across two columns on larger screens --}}
            <h2 class="section-title">Recently Added Cars</h2>
            @if(isset($recentlyAddedCars) && $recentlyAddedCars->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Plate</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Added</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentlyAddedCars as $car)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $car->make }} {{ $car->model }} ({{ $car->year }})</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $car->license_plate }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $car->status == 'available' ? 'bg-green-100 text-green-800' : ($car->status == 'unavailable' || $car->status == 'maintenance' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($car->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $car->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.cars.show', $car->id) }}" class="table-action-link">View</a>
                                    <a href="{{ route('admin.cars.edit', $car->id) }}" class="ml-2 table-action-link">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No cars added recently.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Add any specific scripts for this page if needed, e.g., for charts --}}
{{-- Example for Chart.js if you decide to add charts later
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Placeholder for chart initialization
</script>
--}}
@endpush