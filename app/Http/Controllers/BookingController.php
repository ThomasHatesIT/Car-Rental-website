<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest; // Import the Form Request
use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use Illuminate\Http\Request; // Keep for other methods if needed, though store uses FormRequest
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Ensure Log facade is imported
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingMail;

class BookingController extends Controller
{
    /**
     * Show the form for creating a new booking.
     * The route should bind the Car model.
     * e.g., Route::get('/bookings/create/{car}', [BookingController::class, 'create'])->name('bookings.create');
     */
    public function create(Car $car) // Use Route Model Binding for $car
    {
        if ($car->status !== 'available') {
            return redirect()->route('home')
                ->with('error', 'This car is currently not available for booking.');
        }

        return view('booking.create', [
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

        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);
        $totalDays = $endDate->diffInDays($startDate, true) + 1;

        if ($totalDays <= 0) {
             return back()->withInput()->with('error', 'Booking duration must be at least 1 day.');
        }

        $dailyRate = $car->price_per_day;
        $subtotal = $dailyRate * $totalDays;
        $taxRate = 0.10;
        $taxAmount = $subtotal * $taxRate;
        $totalAmount = $subtotal + $taxAmount;

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
                'discount_amount' => 0,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $validatedData['notes'] ?? null,
            ]);

         // In App\Http\Controllers\BookingController.php, inside store() method

// ... after $booking = Booking::create([...]);
$booking->load(['user', 'car']); // Eager load the user and car relationships
DB::commit();

if ($booking->user) {
    Log::info('Attempting to send booking confirmation email for booking ID: ' . $booking->id . ' to ' . $booking->user->email);
    // Note: You currently have the recipient hardcoded.
    // If you want it to go to the booking user, use: Mail::to($booking->user->email)->send(new BookingMail($booking));
    Mail::to('thomasbernardo910@gmail.com')->send(new BookingMail($booking));
    Log::info('Mail::send command executed for booking ID: ' . $booking->id);
} else {
    Log::warning('Booking user not found for booking ID: ' . $booking->id . '. Email not sent.');
}
// ...
            // --- END ADDED LOGGING ---

            return redirect()->route('home')
                ->with('success', 'Booking request submitted successfully! Your booking is pending approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            // --- MODIFIED LOGGING FOR EXCEPTION ---
            Log::error('Booking creation or mail sending failed: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString(), [
                'request_data' => $request->all(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine()
            ]);
            // --- END MODIFIED LOGGING ---
            return back()->withInput()->with('error', 'There was an issue submitting your booking. Please try again.');
        }
    }

    /**
     * Display a listing of the user's bookings (My Bookings).
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
                            ->with('car')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('booking.index', [
            'bookings' => $bookings
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // $this->authorize('view', $booking);
        // if (Auth::id() !== $booking->user_id && !Auth::user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized action.');
        // }
        // $booking->load('car', 'user');
        // return view('booking.show', compact('booking'));
        return "Show booking: " . $booking->booking_number;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        // $this->authorize('update', $booking);
        // return view('booking.edit', compact('booking'));
        return "Edit booking: " . $booking->booking_number;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // $this->authorize('update', $booking);
        // Update logic
        // return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated.');
        return "Update booking: " . $booking->booking_number;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        if ($booking->canBeCancelledByUser()) { // Assuming this method exists on your Booking model
            $booking->status = 'cancelled';
            $booking->cancelled_at = now();
            $booking->save();
            return redirect()->route('bookings.index')->with('success', 'Booking cancelled.');
        }
        // Fallback or error if can't be cancelled
        return redirect()->route('bookings.index')->with('error', 'Booking could not be cancelled at this time.');
    }


   public function cancel(Booking $booking)
   {
        $this->authorize('cancel', $booking); // Assuming you have a 'cancel' policy method

        try {
            if ($booking->status !== 'pending' && $booking->status !== 'confirmed') { // Example condition
                return redirect()->route('bookings.index')
                    ->with('error', 'Booking #' . $booking->booking_number . ' cannot be cancelled in its current state.');
            }

            $booking->status = 'cancelled';
            $booking->cancelled_at = now();
            $booking->cancellation_reason = 'Cancelled by user'; // Or get reason from request
            $booking->save();

            // Optionally, send a cancellation confirmation email
            // Mail::to($booking->user->email)->send(new BookingCancelledMail($booking));
            // Log::info('Booking cancellation email sent for booking ID: ' . $booking->id);

            return redirect()->route('bookings.index')
                ->with('success', 'Booking #' . $booking->booking_number . ' has been successfully cancelled.');
        } catch (\Exception $e) {
            Log::error('Booking cancellation failed for booking ID ' . $booking->id . ': ' . $e->getMessage() . ' Stack Trace: ' . $e->getTraceAsString());
            return redirect()->route('bookings.index')
                ->with('error', 'There was an issue cancelling your booking. Please try again or contact support.');
        }
    }
}   