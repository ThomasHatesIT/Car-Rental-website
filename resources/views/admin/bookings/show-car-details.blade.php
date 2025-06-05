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
                    // Get the previous URL. If not available, default to admin cars index.
                    // Using session('previous_url_for_car_show') would be more robust if you set it before navigating.
                    $previousUrl = url()->previous();
                    $defaultBackUrl = route('admin.cars.index');

                    // Ensure previous URL is from the same domain and not the current page to avoid loops
                    $backUrl = ($previousUrl && $previousUrl !== url()->current() && Str::startsWith($previousUrl, url('/')))
                               ? $previousUrl
                               : $defaultBackUrl;

                    // If you specifically passed fromBookingId and want to prioritize that, you could combine:
                    // if (isset($fromBookingId) && $fromBookingId) {
                    //     $backUrl = url('/admin/bookings/' . $fromBookingId);
                    // } elseif ($previousUrl && $previousUrl !== url()->current() && Str::startsWith($previousUrl, url('/'))) {
                    //     $backUrl = $previousUrl;
                    // } else {
                    //     $backUrl = $defaultBackUrl;
                    // }
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

{{-- You'll need to import Str facade at the top of your Blade file if you use Str::startsWith --}}
{{-- @php use Illuminate\Support\Str; @endphp --}}
{{-- Or, more commonly, you'd do this check in the controller and pass the final $backUrl to the view. --}}