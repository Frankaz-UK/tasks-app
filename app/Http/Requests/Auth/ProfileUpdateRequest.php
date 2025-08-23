<?php

namespace App\Http\Requests\Auth;

use App\Enums\Titles;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /** @return array<string, array<Rule|string>|Rule|string> */
    public function rules(): array
    {
        return [
            'title' => Rule::enum(Titles::class),
            'forename' => 'required|string|min:3|max:255',
            'surname' => 'required|string|min:3|max:255',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'position' => 'required|string|min:5|max:255',
            'telephone' => 'required|phone:GB',
            'gender' => 'required|string|in:Male,Female',
        ];
    }

    public function messages(): array
    {
        return [
            'title' => 'The title field is required, and must be from the selection available',
            'forename.required' => 'The forename field is required',
            'forename.string' => 'The forename field must be a string',
            'forename.min' => 'The forename field must be at least :min characters',
            'forename.max' => 'The forename field must be less than :max characters',
            'surname.required' => 'The surname field is required',
            'surname.string' => 'The surname field must be a string',
            'surname.min' => 'The surname field must be at least :min characters',
            'surname.max' => 'The surname field must be less than :max characters',
            'email.required' => 'The email field is required',
            'email.string' => 'The email field must be a string',
            'email.email' => 'The email field must be a valid email address',
            'email.max' => 'The email field must be less than :max characters',
        ];
    }
}
