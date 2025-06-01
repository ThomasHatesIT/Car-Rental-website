<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest; // Import the Form Request
use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use Illuminate\Http\Request; // Keep for other methods if needed, though store uses FormRequest
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Show the form for creating a new booking.
     * The route should bind the Car model.
     * e.g., Route::get('/bookings/create/{car}', [BookingController::class, 'create'])->name('bookings.create');
     */
    public function create(Car $car) // Use Route Model Binding for $car
    {
        // Eager load relationships if not already loaded by default or if specifically needed here
        // $car->loadMissing(['featuredImage', 'images']); // This is good practice

        if ($car->status !== 'available') {
            return redirect()->route('home') // Or wherever you list cars
                ->with('error', 'This car is currently not available for booking.');
        }

        return view('booking.create', [ // Assuming your view is in 'booking/create.blade.php'
            'car' => $car
        ]);
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        $validatedData = $request->validated();

        $car = Car::findOrFail($validatedData['car_id']);

        // Double check availability - validation request should handle this, but good for defense
        // The StoreBookingRequest already performs this check, including isAvailableForDates
        // if ($car->status !== 'available' || !$car->isAvailableForDates(Carbon::parse($validatedData['start_date']), Carbon::parse($validatedData['end_date']))) {
        //      return back()->withInput()->with('error', 'Sorry, this car is no longer available for the selected dates. Please try different dates or another car.');
        // }

        // Calculate total days
        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);
     $totalDays = $endDate->diffInDays($startDate, true) + 1; // Explicitly use absolute: true
     
        if ($totalDays <= 0) {
             return back()->withInput()->with('error', 'Booking duration must be at least 1 day.');
        }

        // Calculate amounts
        $dailyRate = $car->price_per_day;
        $subtotal = $dailyRate * $totalDays;
        $taxRate = 0.10; // 10% tax - consider making this a config value (e.g., config('app.tax_rate'))
        $taxAmount = $subtotal * $taxRate;
        $totalAmount = $subtotal + $taxAmount;
        // discount_amount is 0 by default as per schema for now

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'booking_number' => 'BKNG-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'car_id' => $car->id,
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'pickup_time' => $validatedData['pickup_time'],
                'return_time' => $validatedData['return_time'],
                'pickup_location' => $validatedData['pickup_location'],
                'return_location' => $validatedData['return_location'],
                'total_days' => $totalDays,
                'daily_rate' => $dailyRate,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0, // Explicitly set, though default is 0
                'total_amount' => $totalAmount,
                'status' => 'pending', // Initial status
                'payment_status' => 'pending', // Initial payment status
                'notes' => $validatedData['notes'] ?? null,
            ]);

            DB::commit();

            // TODO: Send notification to user and admin (future step)

            // Redirect to a booking success/pending page or "My Bookings"
            // For now, let's redirect to home with a success message.
            // We'll create a 'my-bookings' page (bookings.index) soon.
            return redirect()->route('home') // Change to 'bookings.index' for "My Bookings" later
                ->with('success', 'Booking request submitted successfully! Your booking is pending approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            // It's good practice to log the actual error for debugging
            \Illuminate\Support\Facades\Log::error('Booking creation failed: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return back()->withInput()->with('error', 'There was an issue submitting your booking. Please try again.');
        }
    }

    /**
     * Display a listing of the user's bookings (My Bookings).
     */
    public function index() // This will be for "My Bookings"
    {
        // Fetch bookings for the authenticated user, ordered by creation date or start date
        $bookings = Booking::where('user_id', Auth::id())
                            ->with('car') // Eager load car details
                            ->orderBy('created_at', 'desc')
                            ->paginate(10); // Paginate for better performance

        return view('booking.index', [ // Assuming your view is 'booking/index.blade.php'
            'bookings' => $bookings
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Booking $booking) // Use Route Model Binding for $booking
    {
        // Authorization: Ensure the user owns this booking or is an admin
        // $this->authorize('view', $booking); // Example if you have a BookingPolicy

        // if (Auth::id() !== $booking->user_id && !Auth::user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized action.');
        // }
        
        // $booking->load('car', 'user'); // Eager load relationships
        // return view('booking.show', compact('booking'));

        // For now, placeholder:
        return "Show booking: " . $booking->booking_number;
    }

    /**
     * Show the form for editing the specified resource.
     * (Likely not for users, maybe for admins to modify details before confirmation)
     */
    public function edit(Booking $booking) // Use Route Model Binding for $booking
    {
        // Authorization
        // $this->authorize('update', $booking);
        // return view('booking.edit', compact('booking'));

        // For now, placeholder:
        return "Edit booking: " . $booking->booking_number;
    }

    /**
     * Update the specified resource in storage.
     * (Likely for admins to confirm/cancel, or users to cancel)
     */
    public function update(Request $request, Booking $booking) // Use Route Model Binding for $booking
    {
        // Authorization
        // $this->authorize('update', $booking);

        // Validation logic for updates

        // Update logic

        // return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated.');

        // For now, placeholder:
        return "Update booking: " . $booking->booking_number;
    }

    /**
     * Remove the specified resource from storage.
     * (More likely 'cancel' rather than hard delete for users)
     */
    public function destroy(Booking $booking) 
    {
       
     $this->authorize('delete', $booking);

       
        if ($booking->canBeCancelledByUser()) {
            $booking->status = 'cancelled';
            $booking->cancelled_at = now();
             // Add cancellation_reason if applicable
             $booking->save();
          return redirect()->route('bookings.index')->with('success', 'Booking cancelled.');
     }

      
        return "Destroy/Cancel booking: " . $booking->booking_number;
    }


   public function cancel(Booking $booking)
{
    $this->authorize('cancel', $booking);

    try {
        $booking->status = 'cancelled';
        $booking->cancelled_at = now();
        $booking->cancellation_reason = 'Cancelled by user';
        $booking->save();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking #' . $booking->booking_number . ' has been successfully cancelled.');
    } catch (\Exception $e) {
        Log::error('Booking cancellation failed: ' . $e->getMessage());
        return redirect()->route('bookings.index')
            ->with('error', 'There was an issue cancelling your booking.');
    }
}
}