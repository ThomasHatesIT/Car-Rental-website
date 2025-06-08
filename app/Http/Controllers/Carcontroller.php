<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Feature;
use App\Models\CarImage;
use Illuminate\Routing\Controller as BaseController; // <--- ADD THIS LINE

class Carcontroller extends BaseController // <--- CHANGE 'Controller' to 'BaseController' (or ensure you use the FQN)
{

    public function __construct(){
       
        $this->middleware('permission:edit cars')->only(['edit', 'update']);

        $this->middleware('permission:delete cars')->only(['destroy']); 
    }


 public function browseCars(Request $request)
    {
        // Start a new query for the Car model
        $query = Car::query();

        // Eager load the images to prevent N+1 query problems
        $query->with('images');

        // Apply search keyword filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('make', 'like', "%{$searchTerm}%")
                  ->orWhere('model', 'like', "%{$searchTerm}%")
                  ->orWhere('year', 'like', "%{$searchTerm}%")
                  ->orWhere('fuel_type', 'like', "%{$searchTerm}%");
            });
        }

        // Apply transmission filter
        if ($request->filled('transmission') && $request->input('transmission') !== 'any') {
            $query->where('transmission', $request->input('transmission'));
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->input('max_price'));
        }
        
        // Apply sorting
        $sortBy = $request->input('sort_by', 'created_at_desc'); // Default sort
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price_per_day', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_day', 'desc');
                break;
            case 'year_desc':
                $query->orderBy('year', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Only show available cars
        $query->where('status', 'available');

        // Paginate the results
        $cars = $query->paginate(9)->withQueryString();

        return view('user.browse-cars.index', compact('cars'));
    }

  




  
    public function home()
    {
        $cars = Car::with(['featuredImage', 'images'])
                     ->where('status', 'available')
                     ->where('is_featured', true)
                     ->take(10)
                     ->get();

        return view('user.home', compact('cars'));
    }

    public function index()
    {
        // Usually lists all cars, perhaps with pagination
        $cars = Car::with('featuredImage')->latest()->paginate(10); // Example
        return view('user.index', compact('cars')); // Assuming you have a user.index view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // You might need this if users can create cars from the frontend
        // and if you apply 'create cars' permission
        // $this->middleware('permission:create cars')->only(['create', 'store']);
        return view('user.create'); // Assuming you have a user.create view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation and storing logic here
        // Example:
        // $validated = $request->validate([...]);
        // Car::create($validated);
        // return redirect()->route('cars.index')->with('success', 'Car created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = Car::with(['features', 'featuredImage', 'images'])->findOrFail($id);
        return view('user.cars.show', compact('car')); // Pass 'car' not an array with 'car' key
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $car = Car::findOrFail($id);
        return view('user.edit', compact('car')); // Assuming you have a user.edit view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $car = Car::findOrFail($id);
        // $validated = $request->validate([...]);
        // $car->update($validated);
        // return redirect()->route('cars.show', $car->id)->with('success', 'Car updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $car = Car::findOrFail($id);
        // $car->delete();
        // return redirect()->route('cars.index')->with('success', 'Car deleted successfully.');
    }
}