{{-- resources/views/admin/bookings/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Bookings')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                        Manage Bookings
                    </h1>
                    <p class="text-gray-600 mt-1">Track and manage all rental bookings</p>
                </div>
            </div>
            {{-- <a href="{{ route('admin.bookings.create') }}" class="group relative px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Booking
                </span>
            </a> --}}
        </div>

        {{-- Enhanced Session Messages --}}
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-6 mb-6 rounded-xl shadow-sm" role="alert">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-400 p-6 mb-6 rounded-xl shadow-sm" role="alert">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        @if(session('info'))
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-400 p-6 mb-6 rounded-xl shadow-sm" role="alert">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-blue-800 font-medium">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Enhanced Filter Form -->
        <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl p-8 mb-8 border border-white/20">
            <div class="flex items-center mb-6">
                <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-800">Filter Bookings</h3>
            </div>
            
            <form method="GET" action="{{ route('admin.bookings.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label for="search_term" class="block text-sm font-semibold text-gray-700">Search</label>
                        <div class="relative">
                            <input type="text" name="search_term" id="search_term" value="{{ request('search_term') }}"
                                   class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 hover:bg-white transition-colors duration-200"
                                   placeholder="Booking #, User, Car">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-semibold text-gray-700">Booking Status</label>
                        <select name="status" id="status" class="block w-full px-4 py-3 border border-gray-200 bg-gray-50/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-white transition-colors duration-200">
                            <option value="">All Statuses</option>
                            @foreach($bookingStatuses as $statusKey => $statusValue)
                                <option value="{{ $statusKey }}" {{ request('status') == $statusKey ? 'selected' : '' }}>
                                    {{ $statusValue }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="payment_status" class="block text-sm font-semibold text-gray-700">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="block w-full px-4 py-3 border border-gray-200 bg-gray-50/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-white transition-colors duration-200">
                            <option value="">All Payments</option>
                             @foreach($paymentStatuses as $pStatusKey => $pStatusValue)
                                <option value="{{ $pStatusKey }}" {{ request('payment_status') == $pStatusKey ? 'selected' : '' }}>
                                    {{ $pStatusValue }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end space-x-3">
                        <button type="submit"
                                class="flex-grow group relative inline-flex justify-center items-center py-3 px-6 border border-transparent shadow-lg text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                            </svg>
                            Filter
                        </button>
                         @if(request()->filled('search_term') || request()->filled('status') || request()->filled('payment_status'))
                            <a href="{{ route('admin.bookings.index') }}" class="group relative py-3 px-6 border border-gray-300 rounded-xl shadow-sm text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Enhanced Table -->
        <div class="bg-white/90 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden border border-white/20">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Booking #</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Car</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Dates</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Booking Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Payment Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($bookings as $index => $booking)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                <td class="px-4 py-6 whitespace-nowrap text-sm text-gray-600 font-medium">
                                    {{ $bookings->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm font-bold">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="group relative text-blue-600 hover:text-blue-800 font-semibold" title="View Booking Details">
                                        <span class="relative z-10">{{ $booking->booking_number }}</span>
                                        <span class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                                    </a>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ substr($booking->user->name ?? 'N', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-gray-900 font-medium">{{ $booking->user->name ?? 'N/A' }}</div>
                                            <div class="text-gray-500 text-xs">{{ $booking->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-4">
                                        @if($booking->car && $booking->car->featuredImage)
                                            <img src="{{ Storage::url($booking->car->featuredImage->path) }}" alt="{{ $booking->car->make }} {{ $booking->car->model }}" class="w-20 h-12 object-cover rounded-xl shadow-lg ring-2 ring-white">
                                        @elseif($booking->car && $booking->car->images->isNotEmpty())
                                             <img src="{{ Storage::url($booking->car->images->first()->path) }}" alt="{{ $booking->car->make }} {{ $booking->car->model }}" class="w-20 h-12 object-cover rounded-xl shadow-lg ring-2 ring-white">
                                        @else
                                            <div class="w-20 h-12 bg-gradient-to-r from-gray-200 to-gray-300 rounded-xl flex items-center justify-center shadow-lg ring-2 ring-white">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-gray-900 font-semibold">{{ $booking->car->make ?? 'N/A' }} {{ $booking->car->model ?? '' }}</div>
                                            <div class="text-gray-500 text-xs">({{ $booking->car->year ?? ''}})</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm">
                                    <div class="space-y-1">
                                        <div class="text-gray-900 font-medium">{{ $booking->start_date->format('M d, Y') }}</div>
                                        <div class="text-gray-500 text-xs flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                            {{ $booking->end_date->format('M d, Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-lg font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                        ${{ number_format($booking->total_amount, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="px-3 py-2 inline-flex text-xs leading-5 font-bold rounded-xl shadow-sm
                                        @if($booking->status == 'pending') bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 ring-1 ring-yellow-300
                                        @elseif($booking->status == 'confirmed') bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 ring-1 ring-blue-300
                                        @elseif($booking->status == 'active') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 ring-1 ring-green-300
                                        @elseif($booking->status == 'completed') bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 ring-1 ring-gray-300
                                        @elseif($booking->status == 'cancelled') bg-gradient-to-r from-red-100 to-pink-100 text-red-800 ring-1 ring-red-300
                                        @else bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 ring-1 ring-gray-300
                                        @endif">
                                        {{ $bookingStatuses[$booking->status] ?? ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="px-3 py-2 inline-flex text-xs leading-5 font-bold rounded-xl shadow-sm
                                        @if($booking->payment_status == 'pending') bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 ring-1 ring-yellow-300
                                        @elseif($booking->payment_status == 'paid') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 ring-1 ring-green-300
                                        @elseif($booking->payment_status == 'failed') bg-gradient-to-r from-red-100 to-pink-100 text-red-800 ring-1 ring-red-300
                                        @elseif($booking->payment_status == 'refunded') bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-800 ring-1 ring-purple-300
                                        @else bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 ring-1 ring-gray-300
                                        @endif">
                                        {{ $paymentStatuses[$booking->payment_status] ?? ucfirst($booking->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="group relative inline-flex items-center px-3 py-2 text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg transition-all duration-200 border border-blue-200 hover:border-blue-600" title="View Details">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <span class="text-xs font-semibold">Show</span>
                                        </a>
                                        @if($booking->status !== 'cancelled' && $booking->status !== 'completed')
                                            <button type="button" onclick="openCancelModal({{ $booking->id }}, '{{ $booking->booking_number }}')"
                                                    class="group relative inline-flex items-center px-3 py-2 text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200 border border-red-200 hover:border-red-600" title="Cancel Booking">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                <span class="text-xs font-semibold">Cancel</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center space-y-4">
                                        <div class="w-16 h-16 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <h3 class="text-lg font-semibold text-gray-700">No bookings found</h3>
                                            <p class="text-sm text-gray-500 mt-1">No bookings match your current criteria.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($bookings->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-2">
                    {{ $bookings->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Enhanced Cancel Booking Modal -->
<div id="cancelBookingModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
            <form id="cancelBookingForm" method="POST"> {{-- Action will be set by JS --}}
                @csrf
                @method('PATCH') {{-- Important: method override for cancel action --}}
                <div class="bg-gradient-to-r from-white to-gray-50 px-6 pt-6 pb-4 sm:p-8 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-gradient-to-r from-red-100 to-pink-100 sm:mx-0 sm:h-12 sm:w-12 shadow-lg">
                            <svg class="h-7 w-7 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-2" id="modal-title">
                                Cancel Booking <span id="modalBookingNumber" class="text-red-600"></span>
                            </h3>
                            <div class="mt-4 space-y-4">
                                <p class="text-sm text-gray-600 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                                    ⚠️ Are you sure you want to cancel this booking? This action cannot be undone.
                                </p>
                                <div>
                                    <label for="cancellation_reason_admin" class="block text-sm font-semibold text-gray-700 mb-2">Cancellation Reason (Admin)</label>
                                    <textarea name="cancellation_reason_admin" id="cancellation_reason_admin" rows="4"
                                              class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-gray-50 hover:bg-white transition-colors duration-200 resize-none"
                                              placeholder="Enter reason for cancellation (optional)..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse sm:gap-3">
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-lg px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-base font-semibold text-white hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Confirm Cancel
                    </button>
                    <button type="button" onclick="closeCancelModal()"
                            class="mt-3 w-full inline-flex justify-center items-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const cancelBookingModal = document.getElementById('cancelBookingModal');
    const cancelBookingForm = document.getElementById('cancelBookingForm');
    const modalBookingNumberSpan = document.getElementById('modalBookingNumber');
    const cancellationReasonTextarea = document.getElementById('cancellation_reason_admin');

    function openCancelModal(bookingId, bookingNumber) {
        // Ensure the base URL is correct for your admin routes
        let actionUrl = `{{ url('admin/bookings') }}/${bookingId}/cancel`;

        // Preserve query parameters (filters) when the form is submitted and redirects
        const currentQueryParams = window.location.search;
        if (currentQueryParams) {
            // Check if actionUrl already has query params (it shouldn't from base)
            actionUrl += currentQueryParams;
        }
        cancelBookingForm.action = actionUrl;
        modalBookingNumberSpan.textContent = '#' + bookingNumber;
        cancellationReasonTextarea.value = ''; // Clear previous reason
        cancelBookingModal.classList.remove('hidden');
        
        // Add smooth fade-in animation
        setTimeout(() => {
            cancelBookingModal.querySelector('.inline-block').classList.add('animate-fadeIn');
        }, 10);
    }

    function closeCancelModal() {
        // Add smooth fade-out animation
        cancelBookingModal.querySelector('.inline-block').classList.remove('animate-fadeIn');
        setTimeout(() => {
            cancelBookingModal.classList.add('hidden');
        }, 200);
    }

    // Close modal on escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape" && !cancelBookingModal.classList.contains('hidden')) {
            closeCancelModal();
        }
    });

    // Close modal when clicking outside
    cancelBookingModal.addEventListener('click', function(event) {
        if (event.target === cancelBookingModal) {
            closeCancelModal();
        }
    });
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out forwards;
    }
    
    /* Custom scrollbar styling */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: linear-gradient(to right, #3b82f6, #6366f1);
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to right, #2563eb, #4f46e5);
    }
</style>
@endpush