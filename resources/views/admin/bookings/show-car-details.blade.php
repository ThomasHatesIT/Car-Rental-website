{{-- resources/views/admin/cars/show.blade.php (or your specific path) --}}

@extends('layouts.admin')

@section('title')
    {{ $car->make }} {{ $car->model }} - Details | {{ config('app.name', 'DriveNow') }}
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                @php
                    // $fromBookingId is passed from the controller (AdminController@carShow).
                    // It was retrieved from the 'from_booking' query parameter.

                    $backUrl = route('admin.cars.index'); // Default fallback to cars index

                    if (isset($fromBookingId) && $fromBookingId) {
                        // Construct the URL directly if you want to be explicit
                        // or if route() helper is causing issues (though it shouldn't if names are correct)
                        $backUrl = url('/admin/bookings/' . $fromBookingId);

                        // Alternatively, and recommended, ensure the route() helper works:
                        // $backUrl = route('admin.bookings.show', ['booking' => $fromBookingId]);
                    }
                @endphp
                <a href="{{ $backUrl }}"
                   class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Go Back
                </a>
            </div>

            <x-car-details-card :car="$car" /> {{-- Or your correct car details component name --}}

        </div>
    </div>
@endsection

{{-- ... @push('scripts') ... --}}