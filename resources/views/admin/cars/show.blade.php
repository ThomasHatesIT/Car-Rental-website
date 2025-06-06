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

                    // This logic correctly sends the user back to the booking if they came from there
                    if (isset($fromBookingId) && $fromBookingId) { // $fromBookingId passed from controller
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

                    {{-- ADD THE "EDIT CAR" BUTTON HERE for the admin view --}}
                    @can('edit cars') {{-- Or your specific permission for editing cars --}}
                        <a href="{{ route('admin.cars.edit', $car) }}"
                           class="inline-flex items-center justify-center px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 order-last sm:order-first sm:mr-3 w-full sm:w-auto">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Car
                        </a>
                    @endcan
                    {{-- END OF "EDIT CAR" BUTTON --}}


                    @if (Auth::user()->hasRole('admin')) {{-- Example: Admin might see different info/actions --}}
                        {{-- This span is optional, just for context --}}
                        {{-- <span class="text-sm text-gray-600 italic">Admin view actions:</span> --}}
                    @endif


                {{-- Show car status for admins without a booking button --}}
@auth
    @if($car->status === 'available')
        <span class="inline-flex items-center justify-center px-6 py-3 bg-green-500 text-white font-semibold rounded-lg w-full sm:w-auto">
            Available
        </span>
    @else
        <span class="inline-flex items-center justify-center px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg w-full sm:w-auto">
            Not Available
        </span>
    @endif
@endauth

                   

                </x-slot>
            </x-car-details-card>
        </div>
    </div>
@endsection