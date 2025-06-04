@extends('layouts.admin')

@section('title', $car->make . ' ' . $car->model . ' - Car Details')

@section('content')
    <x-car-details-card 
        :car="$car" 
        :backUrl="route('admin.cars.index')" 
        backText="Go back" />
@endsection

@push('scripts')
    {{-- The component already pushes its script. If you have other scripts: --}}
@endpush