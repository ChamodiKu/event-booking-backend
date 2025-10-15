<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreEventRequest
 *
 * Handles validation and authorization for creating or updating events.
 */
class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Only organizers and admins can create or update events
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
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:2000',
            'date'        => 'required|date|after_or_equal:today',
            'location'    => 'required|string|max:255',
            'status'      => 'nullable|string|in:upcoming,ongoing,completed,cancelled',
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'    => 'Event title is required.',
            'date.required'     => 'Event date is required.',
            'date.after_or_equal' => 'Event date cannot be in the past.',
            'location.required' => 'Event location is required.',
        ];
    }
}
