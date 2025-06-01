<?php

// app/Policies/BookingPolicy.php
namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
// use Carbon\Carbon; // If you need time-based logic
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Models\Role;
class BookingPolicy
{
    public function cancel(User $user, Booking $booking): bool
    {
        if ($user->id !== $booking->user_id) {
            return false;
        }
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return false;
        }
        if ($booking->cancelled_at !== null) {
            return false;
        }
        // Optional: Time-based restriction
        // $cancellationCutoffHours = 24;
        // $startDateTime = \Carbon\Carbon::parse($booking->start_date->toDateString() . ' ' . $booking->pickup_time->toTimeString());
        // if (\Carbon\Carbon::now()->addHours($cancellationCutoffHours)->gt($startDateTime)) {
        //     return false;
        // }
        return true;
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->hasRole('admin');
    }

    public function delete(User $user, Booking $booking): bool // For hard delete
    {
        return $user->hasRole('admin'); // Example: only admin
    }

    public function updateAdminStatus(User $admin, Booking $booking): bool
{
    return $admin->hasRole('admin'); // Or more specific permissions
}

/**
 * Determine whether the admin can cancel any booking.
 */
public function cancelAdmin(User $admin, Booking $booking): bool
{
    return $admin->hasRole('admin'); // Or more specific permissions
}

}