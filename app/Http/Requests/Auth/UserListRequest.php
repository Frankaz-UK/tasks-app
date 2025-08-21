<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserListRequest extends FormRequest
{
    /** @return array<string, array<Rule|string>|Rule|string> */
    public function rules(): array
    {
        return [
            'term' => 'nullable|string|max:255',
            'role' => 'nullable|string|exists:roles,name',
            'per_page' => 'int|min:15|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'term.max' => 'Please enter a term smaller than 255 characters',
            'role.string' => 'Role must be a string value',
            'role.exists' => 'Role must exist',
            'per_page.int' => 'Please enter a valid integer value for the page number.',
        ];
    }
}
