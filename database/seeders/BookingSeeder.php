<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Car;
use Carbon\Carbon;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();

        $cars = Car::where('status', 'available')->get();

        if ($users->isEmpty() || $cars->isEmpty()) {
            $this->command->warn('No users with "user" role or available cars found. Skipping BookingSeeder.');
            return;
        }

        $numberOfBookings = 50;

        for ($i = 0; $i < $numberOfBookings; $i++) {
            $user = $users->random();
            $car = $cars->random();

            $bookingForFuture = $faker->boolean(70);
            $daysOffset = $faker->numberBetween(1, 90);
            $totalDays = $faker->numberBetween(1, 14);

            if ($bookingForFuture) {
                $startDate = Carbon::now()->addDays($daysOffset);
            } else {
                $startDate = Carbon::now()->subDays($daysOffset + $totalDays);
            }
            $endDate = $startDate->copy()->addDays($totalDays -1);

            $pickupHour = $faker->numberBetween(9, 17);
            $pickupTime = Carbon::createFromTime($pickupHour, $faker->randomElement([0, 15, 30, 45]), 0);
            $returnTime = $pickupTime->copy();

            $locations = ['Airport Terminal 1', 'Downtown Office', 'City Center Mall', 'Main Street Branch'];
            $pickupLocation = $faker->randomElement($locations);
            $returnLocation = $faker->boolean(80) ? $pickupLocation : $faker->randomElement($locations);

            $dailyRate = $car->price_per_day;
            
            // Use a temporary booking instance for calculations
            $tempBooking = new Booking();
            $tempBooking->start_date = $startDate;
            $tempBooking->end_date = $endDate;
            $tempBooking->daily_rate = $dailyRate;
            // Manually set total_days on the temp instance if helper methods rely on it being set
            $tempBooking->total_days = $tempBooking->calculateTotalDays();


            $calculatedTotalDays = $tempBooking->total_days;
            $subtotal = $tempBooking->calculateSubtotal();

            $taxRate = 0.10;
            $taxAmount = $subtotal * $taxRate;
            $discountAmount = $faker->boolean(20) ? $faker->randomFloat(2, 5, max(1, $subtotal * 0.15)) : 0; // Ensure discount is not more than 15% of subtotal
            if ($subtotal <= 0) { // Prevent negative discount if subtotal is 0
                $discountAmount = 0;
            }
            $totalAmount = max(0, $subtotal + $taxAmount - $discountAmount); // Ensure total is not negative

            $bookingStatus = 'pending';
            $paymentStatus = 'pending'; // Default payment status
            $confirmedAt = null;
            $cancelledAt = null;
            $cancellationReason = null;
            $pickupAt = null;
            $returnedAt = null;

            if ($bookingForFuture) {
                $bookingStatus = $faker->randomElement(['pending', 'confirmed']);
                if ($bookingStatus === 'confirmed') {
                    $paymentStatus = $faker->randomElement(['pending', 'paid']); // Use 'paid' from ENUM
                    $confirmedAt = $startDate->copy()->subDays($faker->numberBetween(1, 7));
                }
            } else { // Past bookings
                $bookingStatus = $faker->randomElement(['completed', 'cancelled']); // No 'active' for past, should be completed
                if ($bookingStatus === 'completed') {
                    $paymentStatus = 'paid'; // Use 'paid' from ENUM
                    $confirmedAt = $startDate->copy()->subDays($faker->numberBetween(1, 7));
                    $pickupAt = $startDate->copy()->setTimeFrom($pickupTime);
                    $returnedAt = $endDate->copy()->setTimeFrom($returnTime);
                } elseif ($bookingStatus === 'cancelled') {
                    // Corrected payment statuses for cancelled bookings
                    $paymentStatus = $faker->randomElement(['pending', 'refunded', 'failed']); // Only use ENUM values
                    $cancelledAt = $endDate->copy()->subDays($faker->numberBetween(0, max(0, $totalDays -1) / 2));
                    $cancellationReason = $faker->sentence;
                    if ($faker->boolean(70)) {
                         $confirmedAt = $startDate->copy()->subDays($faker->numberBetween(1, 7));
                    }
                }
            }

            if (Carbon::now()->between($startDate, $endDate) && $bookingStatus === 'confirmed') {
                $bookingStatus = 'active';
                $pickupAt = $startDate->copy()->setTimeFrom($pickupTime);
                // Payment status for active could still be pending or paid
                if ($paymentStatus === 'pending' && $faker->boolean(50)) { // 50% chance active booking is now paid
                    $paymentStatus = 'paid';
                }
            }
             // Ensure paymentStatus is always one of the ENUM values if it was somehow missed
            if (!in_array($paymentStatus, ['pending', 'paid', 'failed', 'refunded'])) {
                $paymentStatus = 'pending'; // Fallback safely
            }


            Booking::create([
                'user_id' => $user->id,
                'car_id' => $car->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'pickup_time' => $pickupTime->format('H:i:s'),
                'return_time' => $returnTime->format('H:i:s'),
                'pickup_location' => $pickupLocation,
                'return_location' => $returnLocation,
                'total_days' => $calculatedTotalDays,
                'daily_rate' => $dailyRate,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'status' => $bookingStatus,
                'payment_status' => $paymentStatus, // This will now be one of the allowed ENUM values
                'notes' => $faker->boolean(30) ? $faker->paragraph : null,
                'cancellation_reason' => $cancellationReason,
                'cancelled_at' => $cancelledAt,
                'confirmed_at' => $confirmedAt,
                'pickup_at' => $pickupAt,
                'returned_at' => $returnedAt,
            ]);
        }
        $this->command->info("Seeded {$numberOfBookings} bookings.");
    }
}