@extends('layouts.app')

@section('title', $car->make . ' ' . $car->model . ' - Car Details')

@section('content')
        <x-car-details-card
        :car="$car"
        backUrl="{{ url()->previous() ?? route('admin.cars.index') }}" {{-- Fallback to previous or a default --}}
        backText="Go Back"
    />
    {{-- You could also use a simple URL: backUrl="/admin/cars" --}}
@endsection