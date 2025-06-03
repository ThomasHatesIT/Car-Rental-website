<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminBookingController extends Controller
{
    // Helper to get status arrays
    private function getBookingStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'active' => 'Active',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    private function getPaymentStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ];
    }

    public function index(Request $request)
    {
        // Eager load relationships for efficiency
        $query = Booking::with(['user', 'car', 'car.featuredImage', 'car.images'])
                        ->orderBy('created_at', 'desc');

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

        $bookings = $query->paginate(15)->withQueryString();

        $bookingStatuses = $this->getBookingStatuses();
        $paymentStatuses = $this->getPaymentStatuses();

        return view('admin.bookings.index', compact('bookings', 'bookingStatuses', 'paymentStatuses'));
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        // Eager load relationships if not already loaded or if you need specific ones for the show page
        $booking->loadMissing(['user', 'car', 'car.featuredImage', 'car.images']);

        $bookingStatuses = $this->getBookingStatuses();
        $paymentStatuses = $this->getPaymentStatuses();

        return view('admin.bookings.show', compact('booking', 'bookingStatuses', 'paymentStatuses'));
    }


    public function updateStatus(Request $request, Booking $booking)
    {
        $bookingStatusesKeys = array_keys($this->getBookingStatuses());
        $paymentStatusesKeys = array_keys($this->getPaymentStatuses());

        $validated = $request->validate([
            'status' => ['sometimes', 'required', Rule::in($bookingStatusesKeys)],
            'payment_status' => ['sometimes', 'required', Rule::in($paymentStatusesKeys)],
        ]);

        $updatedField = null;

        try {
            if ($request->has('status')) {
                // Prevent updating to completed/cancelled if already in that state through this flow
                if ($booking->status === 'completed' || $booking->status === 'cancelled') {
                     return redirect()->route('admin.bookings.show', array_merge(['booking' => $booking->id], request()->query()))
                                ->with('info', 'Booking is already ' . $booking->status . ' and cannot be changed further this way.');
                }
                $booking->status = $validated['status'];
                if ($validated['status'] === 'confirmed' && !$booking->confirmed_at) {
                    $booking->confirmed_at = now();
                }
                // TODO: Add logic for other status transitions if needed
                // e.g., if status becomes 'active', set car status to 'rented'
                // if status becomes 'completed', set car status to 'available' (after inspection)
                $updatedField = 'Booking Status';
            }

            if ($request->has('payment_status')) {
                 // Prevent updating if booking is cancelled or payment already refunded
                if ($booking->status === 'cancelled' || $booking->payment_status === 'refunded') {
                     return redirect()->route('admin.bookings.show', array_merge(['booking' => $booking->id], request()->query()))
                                ->with('info', 'Payment status cannot be changed for a ' . ($booking->status === 'cancelled' ? 'cancelled booking' : 'refunded payment') . '.');
                }
                $booking->payment_status = $validated['payment_status'];
                $updatedField = 'Payment Status';
            }

            $booking->save();

            // TODO: Consider sending notifications to the user

            $successMessage = $updatedField . ' for booking #' . $booking->booking_number . ' updated successfully.';
            // Redirect back to the show page, preserving any query parameters from the original request (though less likely for show page)
            return redirect()->route('admin.bookings.show', array_merge(['booking' => $booking->id], request()->query()))
                             ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error("Admin booking status update failed for booking #{$booking->id}: " . $e->getMessage());
            return redirect()->route('admin.bookings.show', array_merge(['booking' => $booking->id], request()->query()))
                             ->with('error', 'Failed to update ' . strtolower($updatedField ?: 'status') . '.');
        }
    }

    public function cancelBooking(Request $request, Booking $booking)
    {
        $request->validate([
            'cancellation_reason_admin' => 'nullable|string|max:500',
        ]);

        if ($booking->status === 'cancelled') {
            $redirectRoute = $request->headers->get('referer') && str_contains($request->headers->get('referer'), route('admin.bookings.show', $booking, false))
                ? 'admin.bookings.show'
                : 'admin.bookings.index';
            return redirect()->route($redirectRoute, array_merge(['booking' => $booking->id], request()->query()))
                             ->with('info', 'Booking #' . $booking->booking_number . ' is already cancelled.');
        }
        if ($booking->status === 'completed') {
             $redirectRoute = $request->headers->get('referer') && str_contains($request->headers->get('referer'), route('admin.bookings.show', $booking, false))
                ? 'admin.bookings.show'
                : 'admin.bookings.index';
            return redirect()->route($redirectRoute, array_merge(['booking' => $booking->id], request()->query()))
                             ->with('info', 'Booking #' . $booking->booking_number . ' is completed and cannot be cancelled.');
        }


        try {
            $booking->status = 'cancelled';
            $booking->cancelled_at = now();
            $booking->cancellation_reason = $request->input('cancellation_reason_admin', 'Cancelled by Admin');
            $booking->save();

            // TODO:
            // 1. Send notification to user.
            // 2. Process refund if applicable (e.g., if payment_status was 'paid').
            // 3. Make the car available again if status was 'confirmed' or 'active'.

            // Determine where to redirect based on where the cancel request came from
            $redirectRoute = $request->headers->get('referer') && str_contains($request->headers->get('referer'), route('admin.bookings.show', $booking, false))
                ? 'admin.bookings.show'
                : 'admin.bookings.index';

            return redirect()->route($redirectRoute, array_merge(['booking' => $booking->id], request()->query()))
                             ->with('success', 'Booking #' . $booking->booking_number . ' has been cancelled.');
        } catch (\Exception $e) {
            Log::error("Admin booking cancellation failed for booking #{$booking->id}: " . $e->getMessage());
             $redirectRoute = $request->headers->get('referer') && str_contains($request->headers->get('referer'), route('admin.bookings.show', $booking, false))
                ? 'admin.bookings.show'
                : 'admin.bookings.index';
            return redirect()->route($redirectRoute, array_merge(['booking' => $booking->id], request()->query()))
                             ->with('error', 'Failed to cancel booking.');
        }
    }
}