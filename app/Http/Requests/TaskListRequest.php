<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskListRequest extends FormRequest
{
    /** @return array<string, array<Rule|string>|Rule|string> */
    public function rules(): array
    {
        return [
            'term' => 'nullable|string|max:255',
            'user' => 'nullable|int|exists:users,id',
            'per_page' => 'int|min:15|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'term.max' => 'Please enter a term smaller than 255 characters',
            'user.int' => 'User id must be an integer',
            'user.exists' => 'User id must exist',
            'per_page.int' => 'Please enter a valid integer value for the page number.',
        ];
    }
}
