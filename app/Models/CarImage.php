<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Car;

class CarImage extends Model
{
  protected $fillable = ['car_id', 'path', 'is_featured']; // 'car_id' is crucial here

public function car()
{
    return $this->belongsTo(Car::class); // CarImage belongs to a Car
}
}
