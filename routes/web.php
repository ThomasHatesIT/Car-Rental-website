<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Carcontroller;



Route::get('/', [Carcontroller::class, 'home'])->name('home');
Route::get('/car/{id}', [CarController::class, 'show'])->name('cars.show');
