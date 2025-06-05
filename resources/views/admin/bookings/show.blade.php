{{-- e.g., resources/views/admin/bookings/show.blade.php --}}

@extends('layouts.admin') {{-- Or your appropriate admin layout --}}

@section('title')
    Booking Details - #{{ $booking->booking_number }}
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        {{-- The backUrl should ideally be passed from the controller or constructed intelligently --}}
        {{-- For this example, assuming it's for admin bookings index --}}
        <a href="{{ $backUrl ?? route('admin.bookings.index', request()->query()) }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Bookings
        </a>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Booking Details: #{{ $booking->booking_number }}</h1>

    <x-booking-details
        :booking="$booking"
        :booking-statuses="$bookingStatuses ?? null" {{-- Pass from controller if admin --}}
        :payment-statuses="$paymentStatuses ?? null" {{-- Pass from controller if admin --}}
        :index-query-filters="request()->query()" {{-- To preserve filters on form redirects --}}
       
:view-car-url="route('admin.bookings.showCarForBooking', $booking->car_id)"
    
    />
</div>
@endsection

@push('scripts')
    {{-- Add any page-specific scripts here if needed, though the modal script is now in the component --}}
@endpush