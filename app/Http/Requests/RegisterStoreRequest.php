<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'forename' => ['required', 'string', 'min:3', 'max:255'],
            'surname' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'position' => ['required', 'string', 'min:5', 'max:255'],
            'telephone' => ['required'], // (new Phone)->lenient() not working 06/03/2025 just required for now.
            'gender' => ['required', 'string', 'in:Male,Female'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
