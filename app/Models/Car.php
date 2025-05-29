<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory;

      protected $fillable = [
        'make',
        'model',
        'year',
        'color',
        'license_plate',
        'vin',
        'transmission',
        'fuel_type',
        'seats',
        'doors',
        'price_per_day',
        'mileage',
        'description',
        'features',
        'images',
        'status',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price_per_day' => 'decimal:2',
            'features' => 'array',
            'images' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    // Relationships
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBookings(): HasMany
    {
        return $this->hasMany(Booking::class)->whereIn('status', ['confirmed', 'active']);
    }

    // Helper methods
    public function getFullNameAttribute(): string
    {
        return "{$this->year} {$this->make} {$this->model}";
    }

    public function getPrimaryImageAttribute(): ?string
    {
        return $this->images[0] ?? null;
    }

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
