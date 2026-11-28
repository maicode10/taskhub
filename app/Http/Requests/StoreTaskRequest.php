<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // the role:admin middleware on the route already checks the role
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'staff'),
            ],
            'priority'    => ['required', 'in:low,medium,high'],
            'due_date'    => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Please enter a task title.',
            'title.max'            => 'The title must not be longer than 100 characters.',
            'description.max'      => 'The description must not be longer than 1000 characters.',
            'assigned_to.required' => 'Please choose a staff member for this task.',
            'assigned_to.exists'   => 'The selected person is not a valid staff member.',
            'priority.required'    => 'Please choose a priority.',
            'priority.in'          => 'Priority must be low, medium, or high.',
            'due_date.required'    => 'Please choose a due date.',
            'due_date.date'        => 'The due date is not a valid date.',
            'due_date.after_or_equal' => 'The due date cannot be in the past.',
        ];
    }
}