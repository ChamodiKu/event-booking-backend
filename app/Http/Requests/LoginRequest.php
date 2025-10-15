<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 *
 * Validates user login input.
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // anyone can attempt login
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Email address is required.',
            'password.required' => 'Password is required.',
        ];
    }
}
