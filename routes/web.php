<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AdminController; // This is likely your main Admin dashboard controller
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminBookingController;

// Guest Routes: Accessible only to unauthenticated users
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'index'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store']);

    Route::get('/login', [SessionController::class, 'index'])->name('login');
    Route::post('/login', [SessionController::class, 'store']);
});

// Authenticated Routes
// Logout must be accessible to authenticated users
Route::post('/logout', [SessionController::class, 'destroy'])->name('logout')->middleware('auth');

// Public/User Facing Car Routes (accessible to everyone)
Route::get('/', [CarController::class, 'home'])->name('home');
Route::get('/car/{id}', [CarController::class, 'show'])->name('cars.show');
Route::get('/cars', [CarController::class, 'browseCars'])->name('browseCars.index');





Route::middleware('auth')->group(function () {
    Route::get('/bookings/create/{car}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index'); // View user's bookings
     Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
 Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    
});



// Admin Routes: Accessible only to authenticated users with the 'admin' role   
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'adminHome'])->name('dashboard');

    // Admin Car Management
    Route::get('/cars', [AdminController::class, 'index'])->name('cars.index');
    Route::get('/cars/create', [AdminController::class, 'carCreate'])->name('cars.create');
    Route::post('/cars', [AdminController::class, 'carStore'])->name('cars.store'); // For creating new cars
    Route::get('/cars/{car}', [AdminController::class, 'carShow'])->name('cars.show'); // Route model binding for show
    Route::get('/cars/{car}/edit', [AdminController::class, 'edit'])->name('cars.edit'); // Route model binding for edit
    Route::put('/cars/{car}', [AdminController::class, 'update'])->name('cars.update'); // For updating existing cars (uses PUT)
    Route::delete('/cars/{car}', [AdminController::class, 'destroy'])->name('cars.destroy'); // For deleting cars (add this if you have a destroy method)

    
     Route::post('/cars/{car}/set-featured-image/{image}', [AdminController::class, 'setFeaturedImage'])->name('cars.setFeaturedImage');


       Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
       Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
 Route::get('/booking-cars/{car}', [AdminBookingController::class, 'bookingshowcardetails'])
    ->name('bookings.showCarForBooking'); // Full name: admin.bookings.showCarForBooking    
    Route::patch('/bookings/{booking}/update-status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::patch('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancelBooking'])->name('bookings.cancel');

    Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');


});

// Example of a route for authenticated users (non-admin specific, if needed)
// Route::middleware('auth')->group(function() {
//     Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
// });