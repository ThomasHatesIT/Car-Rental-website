{{-- resources/views/admin/cars/show.blade.php --}}
@php use Illuminate\Support\Str; @endphp {{-- Added for Str::startsWith --}}
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
                    $previousUrl = url()->previous();
                    $defaultBackUrl = route('admin.cars.index');
                    $backUrl = ($previousUrl && $previousUrl !== url()->current() && Str::startsWith($previousUrl, url('/')))
                               ? $previousUrl
                               : $defaultBackUrl;

                    if (isset($fromBookingId) && $fromBookingId) { // fromBookingId passed from controller
                         $backUrl = route('admin.bookings.show', ['booking' => $fromBookingId]);
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

            <x-car-details-card :car="$car">
                {{-- Content for the 'actions' slot - Customized for this admin view --}}
                <x-slot name="actions">
                    {{-- NO "Edit Car" button here as per your request for this specific view --}}

                    {{-- You might want to keep the Book Now / Not Available / Login for admins too,
                         or add other admin-specific actions. For example: --}}

                    @if (Auth::user()->hasRole('admin')) {{-- Example: Admin might see different info/actions --}}
                        <span class="text-sm text-gray-600 italic">Admin view </span>
                      <a href="{{ route('admin.cars.show', $car) }}"> View car here </a>
                    @endif


                    {{-- Keep the booking-related buttons if they make sense for an admin viewing car details --}}
                    {{-- This part is the same as the default, just without the @can('edit cars') block --}}
                    @auth
                        {{-- If admin cannot "edit cars" (e.g. a lower-level admin) but can book --}}
                        @cannot('edit cars')
                            @if($car->status === 'available')
                                <a href="{{ route('bookings.create', $car) }}"
                                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm5 1a1 1 0 00-1-1H6a1 1 0 00-1 1v1H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1h-1V3zm-3 7a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Book Now (Admin)
                                </a>
                            @else
                                 <span class="inline-flex items-center justify-center px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed w-full sm:w-auto">
                                    Not Available
                                </span>
                            @endif
                        @endcannot
                    @endauth

                    {{-- Guest section probably not relevant if this is an admin-only view for cars --}}
                    {{-- @guest
                        <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                           class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 w-full sm:w-auto">
                            Login to Book
                        </a>
                    @endguest --}}

               
                </x-slot>
            </x-car-details-card>
        </div>
    </div>
@endsection