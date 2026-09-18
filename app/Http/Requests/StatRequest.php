<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items'               => 'required|array|min:1|max:5',
            'items.*.value'       => 'required|string|max:6',
            'items.*.label'       => 'required|string|max:45',
            'items.*.description' => 'required|string|max:45',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Please add at least 1 stat.',
            'items.min'       => 'Please add at least 1 stat.',
            'items.max'       => 'You can add a maximum of 5 stats.',

            'items.*.value.required' => 'Value is required for each stat.',
            'items.*.value.max'      => 'Value must not exceed 6 characters.',

            'items.*.label.required' => 'Label is required for each stat.',
            'items.*.label.max'      => 'Label must not exceed 45 characters.',

            'items.*.description.required' => 'Description is required for each stat.',
            'items.*.description.max'      => 'Description must not exceed 45 characters.',
        ];
    }
}