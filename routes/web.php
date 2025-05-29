<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Carcontroller;
use App\Http\Controllers\AdminController;


Route::get('/', [Carcontroller::class, 'home'])->name('home');
Route::get('/car/{id}', [CarController::class, 'show'])->name('cars.show');



Route::get('/admin', [AdminController::class, 'adminHome'])->name('admin.dashboard');

Route::get('/admin/cars', [AdminController::class, 'index'])->name('admin.cars.index');

Route::get('/admin/cars/create', [AdminController::class, 'carCreate'])->name('admin.cars.create');
Route::post('/admin/cars', [AdminController::class, 'carStore'])->name('admin.cars.store');
