<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskUserRequest extends FormRequest
{
    /** @return array<string, array<Rule|string>|Rule|string> */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|int|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user.int' => 'User id must be an integer',
            'user.exists' => 'User id must exist',
        ];
    }
}
