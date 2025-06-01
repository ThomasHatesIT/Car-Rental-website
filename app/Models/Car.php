<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CarImage;
use App\Models\Feature; // Assuming Feature is in App\Models

// Import the correct Eloquent relationship types
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // <--- CORRECT ONE
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'make', 'model', 'year', 'color', 'license_plate', 'vin',
        'transmission', 'fuel_type', 'seats', 'doors', 'mileage',
        'price_per_day', 'status', 'is_featured', 'description',
        // 'images', // Generally, you don't put relationship names in $fillable
    ];

    protected function casts(): array
    {
        return [
            'price_per_day' => 'decimal:2',
          
            'is_featured' => 'boolean', // This is for the 'is_featured' column on the 'cars' table itself
        ];
    }

    public function images(): HasMany // Correct type hint
    {
        return $this->hasMany(CarImage::class);
    }

    public function featuredImage(): HasOne // <--- CORRECT TYPE HINT
    {
        return $this->hasOne(CarImage::class)->where('is_featured', true);
    }

    public function features(): BelongsToMany // Correct type hint
    {
        return $this->belongsToMany(Feature::class);
    }

    // Your existing accessor - make sure it's robust
    public function getPrimaryImageAttribute() // Accessor returns a CarImage model or null
    {
        // Ensure the 'images' relationship is loaded for efficiency
        // and to avoid N+1 if this accessor is used in a loop without prior eager loading.
        // It's better to rely on eager loading in the controller: Car::with('images')
        if (!$this->relationLoaded('images')) {
            $this->load('images'); // Load if not already loaded
        }

        // Now $this->images is guaranteed to be a Collection
        $featured = $this->images->firstWhere('is_featured', true);
        if ($featured) {
            return $featured;
        }
        return $this->images->first(); // Fallback to the first image if no featured one
    }


    // Relationships for Bookings (assuming Booking model exists in App\Models)
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBookings(): HasMany
    {
        return $this->hasMany(Booking::class)->whereIn('status', ['confirmed', 'active']);
    }

    // Helper methods
    public function getFullNameAttribute(): string // Assuming you meant this to be make and model
    {
        return "{$this->year} {$this->make} {$this->model}";
    }

    // public function getPrimaryImageAttribute(): ?string // Original version returning string
    // {
    //     // This was problematic if $this->images was supposed to be your JSON column.
    //     // Since you have a proper CarImage relationship, the accessor above is better.
    //     return $this->images[0] ?? null; // This would be for a JSON 'images' column
    // }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isAvailableForDates($startDate, $endDate): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        return !$this->bookings()
            ->where('status', '!=', 'cancelled')
            ->where(function (Builder $query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function (Builder $q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
    }
}