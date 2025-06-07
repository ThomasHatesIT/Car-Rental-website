<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest; // Import the Form Request
use App\Models\User; // Not directly used in store, but good to keep if other methods use it
use App\Models\Car;
use App\Models\Booking;
// use Illuminate\Http\Request; // StoreBookingRequest is used for store method
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingMail;


class BookingController extends Controller
{
     public function __construct(){
        // Consider applying permissions to more methods like edit, update, destroy, carShow, carStore
       
        $this->middleware('permission:edit bookings')->only(['edit', 'update']);
        $this->middleware('permission:delete booking')->only(['destroy']);
        $this->middleware('permission:cancel bookings')->only(['destroy']);
        // Add $this->middleware('permission:delete cars')->only(['destroy']); when you implement it
    }
    /**
     * Show the form for creating a new booking.
     */
    public function create(Car $car)
    {
        if ($car->status !== 'available') {
            return redirect()->route('home')
                ->with('error', 'This car is currently not available for booking.');
        }

        // Optional: Check if this user already has a pending/confirmed booking for this car
        $existingBookingForThisCar = Booking::where('user_id', Auth::id())
            ->where('car_id', $car->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBookingForThisCar) {
            // You might want to allow them to proceed to the form but show a message,
            // or redirect them with a specific message.
            // For now, let's add a flash message to the view.
            session()->flash('warning', 'You already have an active or pending booking request for this car. You can still submit a new request if dates are different, but it will be subject to review.');
            // Or, if you want to be stricter on the create form itself:
            // return redirect()->route('cars.show', $car->id) // Or back()
            //     ->with('error', 'You already have an active or pending booking for this car.');
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
        $userId = Auth::id();

        // 1. Check if this user already has a pending or confirmed booking for THIS car.
        $existingBooking = Booking::where('user_id', $userId)
                                ->where('car_id', $car->id)
                                ->whereIn('status', ['pending', 'confirmed']) // Check against active/pending states
                                ->first();

        if ($existingBooking) {
            return back()->withInput()->with('error', 'You already have an active or pending booking for this car. Please wait for its resolution or cancel it if you wish to make a new booking for this specific car.');
        }

        // Note: The StoreBookingRequest *could* also contain complex date availability validation
        // (e.g., preventing any overlap for the same car across all users).
        // Based on your requirement ("all users can book what time they want... admin will choose"),
        // such strict validation should be omitted or adjusted in StoreBookingRequest
        // to allow overlapping 'pending' bookings for the admin to review.

        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);
        $totalDays = $endDate->diffInDays($startDate, true) + 1; // Ensure it's inclusive

        if ($totalDays <= 0) {
             return back()->withInput()->with('error', 'Booking duration must be at least 1 day.');
        }

        $dailyRate = $car->price_per_day;
        $subtotal = $dailyRate * $totalDays;
        $taxRate = 0.10; // Consider making this a config value
        $taxAmount = $subtotal * $taxRate;
        $totalAmount = $subtotal + $taxAmount;

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'booking_number' => 'BKNG-' . strtoupper(Str::random(8)), // Or use the boot method in Booking model
                'user_id' => $userId,
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
                'discount_amount' => 0, // Assuming default
                'total_amount' => $totalAmount,
                'status' => 'pending', // All new bookings are pending admin approval
                'payment_status' => 'pending',
                'notes' => $validatedData['notes'] ?? null,
            ]);

            $booking->load(['user', 'car']); // Eager load for the Mailable
            DB::commit();

            // Send notification to admin
            $adminEmailAddress = 'thomasbernardo910@gmail.com'; // Consider making this configurtable

               Mail::to($adminEmailAddress)->queue(new BookingMail($booking));
               
            return redirect()->route('home')
                ->with('success', 'Booking request submitted successfully! Your booking is pending approval by the admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation or mail sending failed: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString(), [
                'request_data' => $request->all(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine()
            ]);
            return back()->withInput()->with('error', 'There was an issue submitting your booking. Please try again.');
        }
    }

    /**
     * Display a listing of the user's bookings (My Bookings).
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
                            ->with('car') // Eager load car details
                            ->orderBy('created_at', 'desc')
                            ->paginate(10); // Paginate for better performance

        return view('booking.index', [
            'bookings' => $bookings
        ]);
    }


    /**
     * Display the specified resource.
     */
   // In your AdminBookingController.php

public function show(Booking $booking)
{
    $booking->load(['user', 'car.featuredImage', 'car.images']); // Eager load necessary relations

    // Define these arrays, likely from a config, enum, or helper
    $bookingStatuses = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'active' => 'Active', // Car picked up
        'completed' => 'Completed', // Car returned
        'cancelled' => 'Cancelled',
    ];
    $paymentStatuses = [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ];

    return view('booking.show', compact('booking', 'bookingStatuses', 'paymentStatuses'));
}

    /**
     * Show the form for editing the specified resource.
     * (Likely not for users, maybe for admins to modify details before confirmation)
     */
    public function edit(Booking $booking)
    {
        // Typically admin functionality
        // $this->authorize('update', $booking); // Requires BookingPolicy
        // if (!Auth::user()->hasRole('admin')) {
        //     abort(403);
        // }
        // return view('booking.edit', compact('booking'));
        return "Edit booking form for booking: " . $booking->booking_number . " (Admin feature placeholder)";
    }

    /**
     * Update the specified resource in storage.
     * (Likely for admins to confirm/cancel, or users to cancel)
     */
    public function update(Request $request, Booking $booking) // Laravel's Request, not StoreBookingRequest
    {
        // Typically admin functionality
        // $this->authorize('update', $booking);
        // if (!Auth::user()->hasRole('admin')) {
        //     abort(403);
        // }
        // Validation logic for updates
        // Update logic
        // return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated.');
        return "Update booking: " . $booking->booking_number . " (Admin feature placeholder)";
    }

    /**
     * User cancels their own booking.
     */
   public function cancel(Booking $booking)
   {
        // Ensure the authenticated user owns this booking
        if (Auth::id() !== $booking->user_id) {
            return redirect()->route('bookings.index')->with('error', 'You are not authorized to cancel this booking.');
        }

        // Use the canBeCancelledByUser method from the Booking model
        if (!$booking->canBeCancelledByUser()) {
            return redirect()->route('bookings.index')->with('error', 'This booking cannot be cancelled at this time (e.g., it may be too close to the start date, already active, or already cancelled).');
        }

        try {
            $booking->status = 'cancelled'; // Or use a constant/enum if defined
            $booking->cancelled_at = now();
            $booking->cancellation_reason = $booking->cancellation_reason ?? 'Cancelled by user'; // Keep existing reason if admin cancelled, or set new
            $booking->save();

            // Optionally, send a cancellation confirmation email to user and/or admin
            // Mail::to($booking->user->email)->send(new BookingCancelledUserNotificationMail($booking));
            // Mail::to('admin@example.com')->send(new BookingCancelledAdminNotificationMail($booking));
            // Log::info('Booking cancellation processed for booking ID: ' . $booking->id);

            return redirect()->route('bookings.index')
                ->with('success', 'Booking #' . $booking->booking_number . ' has been successfully cancelled.');
        } catch (\Exception $e) {
            Log::error('Booking cancellation failed for booking ID ' . $booking->id . ': ' . $e->getMessage());
            return redirect()->route('bookings.index')
                ->with('error', 'There was an issue cancelling your booking. Please try again or contact support.');
        }
    }

    // Note: A 'destroy' method for hard-deleting bookings would typically be admin-only
    // and separate from a user 'cancel' action.
}