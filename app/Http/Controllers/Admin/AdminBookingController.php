<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of all bookings for the admin.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'car'])->orderBy('created_at', 'desc');

        // Basic Filtering Examples (you can expand this)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('booking_number', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('email', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('car', function ($carQuery) use ($searchTerm) {
                      $carQuery->where('make', 'like', "%{$searchTerm}%")
                               ->orWhere('model', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $bookings = $query->paginate(15)->withQueryString(); // withQueryString() keeps filter params in pagination links

        // Possible statuses for dropdowns in the view
        $bookingStatuses = ['pending', 'confirmed', 'active', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

        return view('admin.bookings.index', compact('bookings', 'bookingStatuses', 'paymentStatuses'));
    }

    /**
     * Update the status of a booking.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        // Optional: Authorize using a policy if you have granular admin permissions
        // $this->authorize('updateAdmin', $booking); // Example policy method

        $validated = $request->validate([
            'status' => ['sometimes', 'required', Rule::in(['pending', 'confirmed', 'active', 'completed', 'cancelled'])],
            'payment_status' => ['sometimes', 'required', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
        ]);

        try {
            if ($request->has('status')) {
                $booking->status = $validated['status'];
                if ($validated['status'] === 'confirmed' && !$booking->confirmed_at) {
                    $booking->confirmed_at = now();
                }
                // Add logic for other status transitions if needed (e.g., setting pickup_at, returned_at)
            }

            if ($request->has('payment_status')) {
                $booking->payment_status = $validated['payment_status'];
            }

            $booking->save();

            // TODO: Consider sending notifications to the user about status changes

            return redirect()->route('admin.bookings.index', request()->query())->with('success', 'Booking #' . $booking->booking_number . ' status updated successfully.');
        } catch (\Exception $e) {
            Log::error("Admin booking status update failed for booking #{$booking->id}: " . $e->getMessage());
            return redirect()->route('admin.bookings.index', request()->query())->with('error', 'Failed to update booking status.');
        }
    }

    /**
     * Cancel a booking (Admin action).
     */
    public function cancelBooking(Request $request, Booking $booking)
    {
        // Optional: Authorize
        // $this->authorize('cancelAdmin', $booking);

        $request->validate([
            'cancellation_reason_admin' => 'nullable|string|max:500',
        ]);

        if ($booking->status === 'cancelled') {
            return redirect()->route('admin.bookings.index', request()->query())->with('info', 'Booking #' . $booking->booking_number . ' is already cancelled.');
        }

        try {
            $booking->status = 'cancelled';
            $booking->cancelled_at = now();
            $booking->cancellation_reason = $request->input('cancellation_reason_admin', 'Cancelled by Admin');
            $booking->save();

            // TODO:
            // 1. Send notification to user.
            // 2. Process refund if applicable (this is a complex step often involving payment gateway APIs).
            // 3. Make the car available again for the cancelled dates (if status was confirmed/active).

            return redirect()->route('admin.bookings.index', request()->query())->with('success', 'Booking #' . $booking->booking_number . ' has been cancelled.');
        } catch (\Exception $e) {
            Log::error("Admin booking cancellation failed for booking #{$booking->id}: " . $e->getMessage());
            return redirect()->route('admin.bookings.index', request()->query())->with('error', 'Failed to cancel booking.');
        }
    }
}