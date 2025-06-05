{{-- resources/views/cars/show.blade.php (or wherever your show view is) --}}

@extends('layouts.admin') {{-- Or your actual admin layout file name --}}

@section('title')
    {{ $car->make }} {{ $car->model }} - Details | {{ config('app.name', 'DriveNow') }}
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                {{--
                    Using route('admin.cars.index') is generally more robust than a hardcoded URL.
                    Ensure you have a route named 'admin.cars.index'.
                    If you prefer the hardcoded URL as per your example, use href="/admin/cars".
                --}}
                <a href="/admin/cars" {{-- Or href="/admin/cars" --}}
                   class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Go Back
                </a>
            </div>

            {{-- Use the car details component --}}
            <x-car-details-card :car="$car" />

        </div>
    </div>
@endsection

@push('scripts')
{{--
    If you have specific JavaScript for this page (e.g., image gallery lightbox),
    you can push it to a 'scripts' stack defined in your layout.
--}}
{{-- Example:
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize a lightbox for images or another specific gallery
    });
</script>
--}}
@endpush