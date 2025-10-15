<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreBookingRequest
 *
 * Handles validation and authorization for booking tickets.
 */
class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Only customers can create bookings
        return $this->user() && $this->user()->role === 'customer';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'quantity.required' => 'Ticket quantity is required.',
            'quantity.integer'  => 'Ticket quantity must be an integer value.',
            'quantity.min'      => 'You must book at least one ticket.',
        ];
    }
}
