<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController; // Corrected casing: Carcontroller -> CarController
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;

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


// Admin Routes: Accessible only to authenticated users with the 'admin' role
// Assumes you have a role named 'admin' created via Spatie
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/', [AdminController::class, 'adminHome'])->name('dashboard');

    // Admin Car Management
    Route::get('/cars', [AdminController::class, 'index'])->name('cars.index');
    Route::get('/cars/create', [AdminController::class, 'carCreate'])->name('cars.create');
    Route::post('/cars', [AdminController::class, 'carStore'])->name('cars.store');
    Route::get('/cars/{id}', [AdminController::class, 'carShow'])->name('cars.show');
  
});

// Example of a route for authenticated users (non-admin specific, if needed)
// Route::middleware('auth')->group(function() {
//     Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
// });