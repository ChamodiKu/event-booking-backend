<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreTicketRequest
 *
 * Handles validation and authorization for ticket creation and updates.
 */
class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Only organizers or admins can manage tickets
        return $this->user() && in_array($this->user()->role, ['organizer', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type'     => 'required|string|max:100',
            'price'    => 'required|numeric|min:0',
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
            'type.required'     => 'Ticket type is required.',
            'price.required'    => 'Ticket price is required.',
            'quantity.required' => 'Ticket quantity is required.',
        ];
    }
}
