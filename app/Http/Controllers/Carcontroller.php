<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
class Carcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function home() // Or whatever your method is
{
    // Eager load the necessary relationships
    $cars = Car::with(['featuredImage', 'images']) // Eager load both in case featuredImage is null
                 ->where('status', 'available') // Only show available cars
                 // ->where('is_featured', true) // If you only want "featured" cars on the homepage
                 ->take(10) // Example: limit the number of cars for the swiper
                 ->get();

    return view('user.home', compact('cars'));
}
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $car = Car::findOrFail($id);

        return view('user.show', [
           'car' => $car
        ]);
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
