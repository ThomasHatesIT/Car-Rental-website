<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Booking;
class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create($id)
    {
        $car = Car::with(['featuredImage', 'images'])->findOrFail($id);

        return view('booking.create', [
            'car' => $car
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
        $bookings = Booking::all();

        return view('booking.index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
