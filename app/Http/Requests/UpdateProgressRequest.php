<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        // the role:staff middleware on the route already checks the role
        return true;
    }

    public function rules(): array
    {
        return [
            // whole number from 0 to 100 only
            'progress' => ['required', 'integer', 'between:0,100'],
        ];
    }

    public function messages(): array
    {
        return [
            'progress.required' => 'Please enter your progress.',
            'progress.integer'  => 'Progress must be a whole number.',
            'progress.between'  => 'Progress must be between 0 and 100.',
        ];
    }
}