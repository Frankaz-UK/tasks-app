<?php

namespace App\Http\Requests\Auth;

use App\Enums\Titles;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdatePasswordRequest extends FormRequest
{
    /** @return array<string, array<Rule|string>|Rule|string> */
    public function rules(): array
    {
        return [
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string|min:8',
            'current_password' => 'required|current_password',
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'The password confirmation does not match',
            'password.required' => 'The password field is required',
            'password.min' => 'The password field must be at least :min characters',
            'password_confirmation.min' => 'The password confirmation field must be at least :min characters',
            'password_confirmation.required' => 'The password confirmation field is required',
            'current_password.required' => 'The current password field is required',
            'current_password.current_password' => 'The current password is not correct',
        ];
    }
}
