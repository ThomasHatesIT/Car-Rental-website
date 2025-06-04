<?php

namespace App\Http\Requests;

use App\Models\Car; // Make sure Car is imported if used directly, though not in rules now
use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Validation\Rule; // Not used in the provided rules
use Carbon\Carbon; // Keep if other parts of your app use it, or for 'after_or_equal:today'
use App\Models\Booking; // Keep for type hinting if you add other custom rules, or remove if not used elsewhere in this file

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
    public function rules()
    {
        return [
            'car_id' => ['required', 'exists:cars,id'],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                // ---- CUSTOM OVERLAP VALIDATION REMOVED ----
            ],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pickup_time' => ['required'], // Consider adding 'date_format:H:i' or 'date_format:H:i:s'
            'return_time' => ['required'], // Consider adding 'date_format:H:i' or 'date_format:H:i:s'
            'pickup_location' => ['required', 'string', 'max:255'],
            'return_location' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.after_or_equal' => 'The pickup date must be today or a future date.',
            'end_date.after_or_equal' => 'The return date must be on or after the pickup date.',
            'car_id.exists' => 'The selected car is invalid.',
            // Add messages for pickup_time and return_time format if you add date_format rule
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // No changes needed here for this specific issue
    }
}