@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Bookings</h1>
            <a href="" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Book New Car
            </a>
        </div>

        @if($bookings->count() > 0)
            <!-- Filter Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('bookings.index') }}" 
                           class="py-2 px-1 border-b-2 {{ !request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                            All Bookings
                        </a>
                        <a href="{{ route('bookings.index', ['status' => 'pending']) }}" 
                           class="py-2 px-1 border-b-2 {{ request('status') == 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                            Pending
                        </a>
                        <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}" 
                           class="py-2 px-1 border-b-2 {{ request('status') == 'confirmed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                            Confirmed
                        </a>
                        <a href="{{ route('bookings.index', ['status' => 'active']) }}" 
                           class="py-2 px-1 border-b-2 {{ request('status') == 'active' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                            Active
                        </a>
                        <a href="{{ route('bookings.index', ['status' => 'completed']) }}" 
                           class="py-2 px-1 border-b-2 {{ request('status') == 'completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                            Completed
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Bookings List -->
            <div class="space-y-4">
                @foreach($bookings as $booking)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 mr-3">
                                        {{ $booking->car->name }}
                                    </h3>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($booking->status == 'active') bg-green-100 text-green-800
                                        @elseif($booking->status == 'completed') bg-gray-100 text-gray-800
                                        @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <span class="ml-2 px-3 py-1 text-xs font-medium rounded-full
                                        @if($booking->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->payment_status == 'paid') bg-green-100 text-green-800
                                        @elseif($booking->payment_status == 'failed') bg-red-100 text-red-800
                                        @elseif($booking->payment_status == 'refunded') bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-gray-600">
                                    <div>
                                        <span class="font-medium">Booking #:</span> {{ $booking->booking_number }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Dates:</span> 
                                        {{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Duration:</span> {{ $booking->total_days }} days
                                    </div>
                                    <div>
                                        <span class="font-medium">Total:</span> 
                                        <span class="font-bold text-gray-900">${{ number_format($booking->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 lg:mt-0 lg:ml-6">
                                <div class="flex space-x-3">
                                    <a href="{{ route('bookings.show', $booking) }}" 
                                       class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                        View Details
                                    </a>
                                    @if($booking->status == 'pending' && $booking->payment_status == 'pending')
                                        <a href="{{ route('payments.show', $booking) }}" 
                                           class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Pay Now
                                        </a>
                                    @endif
                                    @if(in_array($booking->status, ['pending', 'confirmed']) && !$booking->cancelled_at)
                                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700"
                                                    onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by booking your first car.</p>
                <div class="mt-6">
                    <a href="" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Browse Cars
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
