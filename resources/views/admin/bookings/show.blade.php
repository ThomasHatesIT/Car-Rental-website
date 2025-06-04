@extends('layouts.admin')

@section('title', 'Booking Details - ' . $booking->booking_number)

@section('content')
    <x-booking-details
        :booking="$booking"
        :booking-statuses="$bookingStatuses" {{-- Ensure this is passed from your AdminBookingController@show --}}
        :payment-statuses="$paymentStatuses" {{-- Ensure this is passed from your AdminBookingController@show --}}
        :back-url="route('admin.bookings.index', request()->query())" {{-- Construct the back URL here --}}

        :index-query-filters="request()->query()" {{-- Pass current query params for form redirects --}}
    />
@endsection