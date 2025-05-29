<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
class Carcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
{
    $cars = Car::where('status', 'available')->get();
    
    return view('user.home', [
        'cars' => $cars
    ]);
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
