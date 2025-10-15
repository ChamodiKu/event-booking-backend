<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterRequest
 *
 * Validates user registration input.
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // anyone can register
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'nullable|string|in:customer,organizer,admin',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Name is required.',
            'email.required'    => 'Email is required.',
            'email.unique'      => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed'=> 'Password confirmation does not match.',
        ];
    }
}
