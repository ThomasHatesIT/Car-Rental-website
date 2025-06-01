<?php

namespace App\Http\Requests;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only authenticated users can make bookings
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $today = Carbon::today()->toDateString();
        $carId = $this->input('car_id');
        $car = Car::find($carId); // Fetch the car to check its status

        return [
            'car_id' => ['required', 'exists:cars,id'],
            'start_date' => ['required', 'date', "after_or_equal:{$today}"],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pickup_time' => ['required', 'date_format:H:i'],
            'return_time' => ['required', 'date_format:H:i'],
            'pickup_location' => ['required', 'string', 'max:255'],
            'return_location' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            // Custom rule to check car availability and status
            'start_date' => [
                function ($attribute, $value, $fail) use ($car) {
                    if (!$car) {
                        // This should ideally be caught by 'exists:cars,id' but good to have a fallback
                        return $fail('The selected car does not exist.');
                    }
                    if ($car->status !== 'available') {
                        return $fail('This car is currently not available for booking.');
                    }
                    if (!$car->isAvailableForDates(Carbon::parse($value), Carbon::parse($this->input('end_date')))) {
                        return $fail('The selected car is not available for the chosen dates.');
                    }
                }
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.after_or_equal' => 'The pickup date must be today or a future date.',
            'end_date.after_or_equal' => 'The return date must be on or after the pickup date.',
            'car_id.exists' => 'The selected car is invalid.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * This can be used to merge default/calculated values before validation
     * or to modify input data.
     */
    protected function prepareForValidation(): void
    {
        // You could potentially set default times here if not provided,
        // but 'required' rule makes them mandatory.
    }
}