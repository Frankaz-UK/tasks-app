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
            'per_page' => 'int|min:15|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'term.max' => 'Please enter a term smaller than 255 characters',
            'per_page.int' => 'Please enter a valid integer value for the page number.',
        ];
    }
}
