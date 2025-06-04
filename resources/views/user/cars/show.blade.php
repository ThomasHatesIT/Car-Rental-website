{{-- Extends your public layout --}}
@extends('layouts.app') {{-- Or your public layout --}}

@section('content')
<x-car-details-card
    :car="$car"
    :back-url="route('home')" {{-- <<<<<<<< CORRECTED THIS LINE --}}
    back-text="Go back"
/>
@endsection

@push('scripts')
    {{-- The component already pushes its script. If you have other scripts: --}}
    {{-- <script> console.log('Public car show page script loaded'); </script> --}}
@endpush