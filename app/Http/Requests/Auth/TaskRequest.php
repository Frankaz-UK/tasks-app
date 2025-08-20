<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    /** @return array<string, array<Rule|string>|Rule|string> */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'description' => 'string|min:5',
            'user_id' => 'nullable|int|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Please enter a name smaller than 255 characters',
            'name.string' => 'Please enter a name',
            'user.int' => 'User id must be an integer',
            'user.exists' => 'User id must exist',
            'description.min' => 'Please enter a description bigger than 5 characters',
            'description.string' => 'Please enter a description',
        ];
    }
}
