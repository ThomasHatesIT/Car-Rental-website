<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('color');
            $table->string('license_plate')->unique();
            $table->string('vin')->unique();
            $table->enum('transmission', ['manual', 'automatic']);
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid']);
            $table->integer('seats');
            $table->integer('doors');
            $table->decimal('price_per_day', 8, 2);
            $table->integer('mileage')->default(0);
            $table->text('description')->nullable();
       
            $table->json('images')->nullable(); // Array of image URLs
            $table->enum('status', ['available', 'rented', 'maintenance', 'out_of_service'])->default('available');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
