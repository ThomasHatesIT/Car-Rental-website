@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Bookings</h1>
                        <p class="text-gray-600">Manage your car rental bookings</p>
                    </div>
                    <div class="mt-6 lg:mt-0">
                        <a href="" {{-- Assuming 'home' lists cars for booking --}}
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Book New Car
                        </a>
                    </div>
                </div>
            </div>

            @if($bookings->total() > 0) {{-- Check total() for paginated results --}}
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8"> {{-- Adjusted to 3 columns --}}
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-400">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                                {{-- To get count of specific status from paginated collection, you might need to query separately or pass from controller --}}
                                {{-- For simplicity, if $bookings is filtered, this count is direct. If not, this shows total of current page. --}}
                                {{-- A more accurate way if $bookings is paginated and not pre-filtered for this stat: --}}
                                <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->bookings()->where('status', 'pending')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-400">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Active Bookings</p>
                                <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->bookings()->where('status', 'active')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-400">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                                <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->bookings()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="bg-white rounded-2xl shadow-lg mb-8">
                    <div class="px-6 pt-6">
                        <nav class="flex flex-wrap gap-1">
                            <a href="{{ route('bookings.index') }}"
                               class="px-6 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ !request('status') && !request('payment_status') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-100' }}">
                                All Bookings
                            </a>
                            <a href="{{ route('bookings.index', ['status' => 'pending']) }}"
                               class="px-6 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request('status') == 'pending' ? 'bg-yellow-500 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-100' }}">
                                Pending
                            </a>
                            <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}"
                               class="px-6 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request('status') == 'confirmed' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-100' }}">
                                Confirmed
                            </a>
                            <a href="{{ route('bookings.index', ['status' => 'active']) }}"
                               class="px-6 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request('status') == 'active' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-100' }}">
                                Active
                            </a>
                            <a href="{{ route('bookings.index', ['status' => 'completed']) }}"
                               class="px-6 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request('status') == 'completed' ? 'bg-gray-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-100' }}">
                                Completed
                            </a>
                             <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}"
                               class="px-6 py-3 rounded-xl font-medium text-sm transition-all duration-200 {{ request('status') == 'cancelled' ? 'bg-red-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-100' }}">
                                Cancelled
                            </a>
                        </nav>
                    </div>
                    <div class="px-6 pb-2">
                        <div class="h-px bg-gray-200 mt-4"></div>
                    </div>
                </div>

                <!-- Bookings List -->
                <div class="space-y-6">
                    @forelse($bookings as $booking) {{-- Use forelse for easier empty state within the loop area if needed --}}
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-8">
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center mb-4">
                                        <div class="flex items-center mb-2 sm:mb-0">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                                                {{-- Car Icon or Image --}}
                                                @if($booking->car && $booking->car->featuredImage)
                                                    <img src="{{ Storage::url($booking->car->featuredImage->path) }}" alt="{{ $booking->car->name }}" class="w-full h-full object-cover rounded-xl">
                                                @elseif($booking->car && $booking->car->images->isNotEmpty())
                                                    <img src="{{ Storage::url($booking->car->images->first()->path) }}" alt="{{ $booking->car->name }}" class="w-full h-full object-cover rounded-xl">
                                                @else
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900">
                                                {{ $booking->car->make ?? 'Car details not available' }}   {{ $booking->car->model ?? 'Car details not available' }}{{ $booking->car->year ?? 'Car details not available' }}
                                            </h3>
                                        </div>
                                        <div class="flex items-center space-x-3 sm:ml-auto">
                                            <span class="px-4 py-2 text-xs font-semibold rounded-full
                                                @if($booking->status == 'pending') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800
                                                @elseif($booking->status == 'confirmed') bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800
                                                @elseif($booking->status == 'active') bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                                @elseif($booking->status == 'completed') bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800
                                                @elseif($booking->status == 'cancelled') bg-gradient-to-r from-red-100 to-red-200 text-red-800
                                                @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 p-6 bg-gray-50 rounded-xl">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Booking #</p>
                                                <p class="text-sm font-bold text-gray-900">{{ $booking->booking_number }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l6-6m-6 0l6 6"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Dates</p>
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Duration</p>
                                                <p class="text-sm font-bold text-gray-900">{{ $booking->total_days }} {{ Str::plural('day', $booking->total_days) }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Total Amount</p>
                                                <p class="text-lg font-bold text-gray-900">${{ number_format($booking->total_amount, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                             <div class="mt-6 xl:mt-0 xl:ml-8">
                                                    <div class="flex flex-col sm:flex-row xl:flex-col space-y-3 sm:space-y-0 sm:space-x-3 xl:space-x-0 xl:space-y-3">
                                                        <a href="" {{-- Assuming you have/want a show route --}}
                                                        class="px-6 py-3 text-sm font-medium bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors duration-200 text-center">
                                                            View Details
                                                        </a>

                                                        {{-- ... Pay Now button ... --}}
  @if($booking->status == 'pending' && $booking->payment_status == 'pending' && !$booking->cancelled_at)
                        {{--
                            You'll need a route for payment processing.
                            Example: route('payments.create', ['booking' => $booking->id])
                            OR route('bookings.payment.initiate', $booking)
                        --}}
                        <a href="" {{-- ADJUST THIS ROUTE --}}
                           class="px-6 py-3 text-sm font-medium bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 text-center shadow-lg">
                            Pay Now (${{ number_format($booking->total_amount, 2) }})
                        </a>
                    @endif

                                                        {{-- Cancel Booking Button --}}
                                                        @can('cancel', $booking) {{-- Using Laravel Policy for authorization --}}
                                                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PATCH') {{-- PATCH is appropriate for this kind of update --}}
                                                                <button type="submit"
                                                                        class="w-full px-6 py-3 text-sm font-medium bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg"
                                                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                                    Cancel Booking
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        {{-- This part is now covered by the main empty state check below --}}
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($bookings->hasPages())
                <div class="mt-12 flex justify-center">
                    <div class="bg-white rounded-2xl shadow-lg p-4">
                        {{ $bookings->appends(request()->query())->links() }} {{-- Append query string for filters --}}
                    </div>
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="text-center py-16 px-8">
                        <div class="mx-auto h-32 w-32 text-gray-300 mb-6">
                            <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        @if(request()->has('status'))
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No bookings found with status "{{ ucfirst(request('status')) }}"</h3>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">Try selecting a different filter or view all bookings.</p>
                        @else
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No bookings found</h3>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">You haven't made any car bookings yet. Start exploring our amazing fleet and book your first car today!</p>
                        @endif
                        <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
                            <a href="" {{-- Assuming 'home' lists cars for booking --}}
                               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Browse Cars
                            </a>
                            {{-- <a href=""
                               class="inline-flex items-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-xl border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Learn More
                            </a> --}}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection